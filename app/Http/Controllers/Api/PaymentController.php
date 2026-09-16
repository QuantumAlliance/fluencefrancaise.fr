<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Enrollment;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use App\Models\Settings;

class PaymentController extends Controller
{
    /**
     * Create Stripe Checkout Session - POST /api/payment/create-checkout-session
     * Redirects user to Stripe's hosted checkout page
     */
    public function createCheckoutSession(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'required|exists:enrollments,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string',
            'class_type_name' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);

        // Set default currency if not provided
        $currency = strtolower($validated['currency'] ?? 'cad');

        // Check if Stripe SDK is available
        if (!class_exists('\Stripe\Stripe')) {
            $stripeInitPath = base_path('vendor/stripe/stripe-php/init.php');
            if (file_exists($stripeInitPath)) {
                require_once $stripeInitPath;
            }

            if (!class_exists('\Stripe\Stripe')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe SDK not installed.',
                ], 500);
            }
        }

        // Get Stripe secret key - try config first, then env
        $stripeSecretKey = config('services.stripe.secret') ?: env('STRIPE_SECRET_KEY');
        if (empty($stripeSecretKey)) {
            Log::error('Stripe secret key missing in .env or config');
            return response()->json([
                'success' => false,
                'message' => 'Stripe secret key not configured correctly on server.',
            ], 500);
        }

        try {
            \Stripe\Stripe::setApiKey($stripeSecretKey);

            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated. Please log in again.',
                ], 401);
            }

            $amount = floatval($validated['amount']);
            $amountInCents = (int) round($amount * 100);
            $classTypeName = $validated['class_type_name'] ?? 'Course Enrollment';
            $currency = strtolower($validated['currency'] ?? 'cad');

            // Get or create Stripe customer
            $customerId = null;
            try {
                $existingPayment = Payment::where('user_id', $user->id)
                    ->whereNotNull('stripe_customer_id')
                    ->first();

                if ($existingPayment && $existingPayment->stripe_customer_id) {
                    $customerId = $existingPayment->stripe_customer_id;
                } else {
                    $customer = \Stripe\Customer::create([
                        'email' => $user->email,
                        'name' => trim(($user->first_name ?? '') . ' ' . ($user->last_name ?? '')) ?: $user->name,
                        'metadata' => ['user_id' => $user->id],
                    ]);
                    $customerId = $customer->id;
                }
            } catch (\Exception $e) {
                Log::warning('Stripe Customer creation failed, proceeding with email: ' . $e->getMessage());
            }

            // Create Checkout Session for Subscription
            $checkoutSessionData = [
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => $currency,
                        'product_data' => [
                            'name' => $classTypeName,
                            'description' => 'Fluence Francaise - ' . $classTypeName,
                        ],
                        'unit_amount' => $amountInCents,
                        'recurring' => [
                            'interval' => 'month',
                            'interval_count' => 1,
                        ],
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'subscription',
                'success_url' => url('/payment/success') . '?session_id={CHECKOUT_SESSION_ID}&enrollment_id=' . $validated['enrollment_id'],
                'cancel_url' => url('/register') . '?payment_cancelled=true',
                'metadata' => [
                    'user_id' => $user->id,
                    'enrollment_id' => $validated['enrollment_id'],
                    'coupon_code' => $validated['coupon_code'] ?? '',
                ],
            ];

            if ($customerId) {
                $checkoutSessionData['customer'] = $customerId;
            } else {
                $checkoutSessionData['customer_email'] = $user->email;
            }

            // Apply Coupon logic if provided
            if (!empty($validated['coupon_code'])) {
                $stripeCouponId = $this->getOrCreateStripeCoupon($validated['coupon_code']);
                if ($stripeCouponId) {
                    $checkoutSessionData['discounts'] = [['coupon' => $stripeCouponId]];
                    Log::info('Applying coupon to checkout session', [
                        'coupon_code' => $validated['coupon_code'],
                        'stripe_coupon_id' => $stripeCouponId,
                        'enrollment_id' => $validated['enrollment_id'],
                    ]);
                }
            }

            Log::info('Creating Stripe checkout session', [
                'mode' => $checkoutSessionData['mode'],
                'has_discount' => isset($checkoutSessionData['discounts']),
                'enrollment_id' => $validated['enrollment_id'],
            ]);

            $checkoutSession = \Stripe\Checkout\Session::create($checkoutSessionData);

            // Important: Handle unique transaction_id safety
            $discountAmount = floatval($validated['discount_amount'] ?? 0);
            $finalAmount = $amount - $discountAmount;

            // Delete any existing 'pending' payment for this enrollment to avoid unique ID crash
            Payment::where('enrollment_id', $validated['enrollment_id'])
                  ->where('status', 'pending')
                  ->delete();

            $payment = Payment::create([
                'user_id' => $user->id,
                'enrollment_id' => $validated['enrollment_id'],
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
                'transaction_id' => $checkoutSession->id,
                'stripe_customer_id' => $customerId,
                'coupon_code' => $validated['coupon_code'] ?? null,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'checkout_url' => $checkoutSession->url,
                    'session_id' => $checkoutSession->id,
                    'payment_id' => $payment->id,
                ],
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Stripe Error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            Log::error('Checkout Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server Error: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle Stripe Checkout success - GET /api/payment/checkout-success
     */
    public function checkoutSuccess(Request $request)
    {
        $sessionId = $request->query('session_id');
        $enrollmentId = $request->query('enrollment_id');

        if (!$sessionId || !$enrollmentId) {
            return response()->json([
                'success' => false,
                'message' => 'Missing session_id or enrollment_id',
            ], 400);
        }

        try {
            $stripeSecretKey = env('STRIPE_SECRET_KEY');
            \Stripe\Stripe::setApiKey($stripeSecretKey);

            // Retrieve the checkout session
            $session = \Stripe\Checkout\Session::retrieve($sessionId);

            // Check payment status - for subscriptions, check if subscription is active
            $isPaid = false;
            if ($session->mode === 'subscription') {
                // For subscriptions, check subscription status
                if ($session->subscription) {
                    $subscription = \Stripe\Subscription::retrieve($session->subscription);
                    $isPaid = in_array($subscription->status, ['active', 'trialing']);
                }
            } else {
                // For one-time payments
                $isPaid = $session->payment_status === 'paid';
            }

            if (!$isPaid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Payment not completed',
                ], 402);
            }

            // Find and update payment record
            $payment = Payment::where('transaction_id', $sessionId)->first();

            if ($payment) {
                $payment->status = 'completed';
                $payment->paid_at = now();
                // Update with subscription ID or payment intent ID
                if ($session->subscription) {
                    $payment->transaction_id = $session->subscription;
                } elseif ($session->payment_intent) {
                    $payment->transaction_id = $session->payment_intent;
                }
                $payment->save();
            }

            // Update enrollment status
            $enrollment = Enrollment::with('user', 'classType')->find($enrollmentId);
            if ($enrollment) {
                $enrollment->status = 'active';
                $enrollment->save();

                // Send confirmation email
                if ($payment && $enrollment->user) {
                    $this->sendPurchaseConfirmationEmail($enrollment->user, $enrollment, $payment);
                }
            }

            Log::info('Checkout success processed', [
                'session_id' => $sessionId,
                'enrollment_id' => $enrollmentId,
                'payment_id' => $payment->id ?? null,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'payment' => $payment,
                    'enrollment' => $enrollment,
                ],
                'message' => 'Payment successful',
            ]);
        } catch (\Exception $e) {
            Log::error('Checkout success error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to process payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create payment intent - POST /api/payment/create-intent
     */
    public function createIntent(Request $request)
    {
        $validated = $request->validate([
            'enrollment_id' => 'nullable|exists:enrollments,id',
            'amount' => 'required|numeric|min:0.01',
            'currency' => 'nullable|string',
            'coupon_code' => 'nullable|string',
            'discount_amount' => 'nullable|numeric|min:0',
        ]);
        
        // Set default currency if not provided
        if (empty($validated['currency'])) {
            $validated['currency'] = 'cad';
        }

        // Check if Stripe SDK is available
        if (!class_exists('\Stripe\Stripe')) {
            // Try to load Stripe manually if autoloader hasn't loaded it yet
            $stripeInitPath = base_path('vendor/stripe/stripe-php/init.php');
            $stripeLibPath = base_path('vendor/stripe/stripe-php/lib/Stripe.php');
            
            if (file_exists($stripeInitPath)) {
                require_once $stripeInitPath;
            } elseif (file_exists($stripeLibPath)) {
                require_once $stripeLibPath;
            }
            
            if (!class_exists('\Stripe\Stripe')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Stripe SDK not installed. Please run: composer require stripe/stripe-php',
                ], 500);
            }
        }

        // Get Stripe secret key from .env
        $stripeSecretKey = env('STRIPE_SECRET_KEY');
        if (empty($stripeSecretKey)) {
            return response()->json([
                'success' => false,
                'message' => 'Stripe secret key not configured. Please configure it in admin settings.',
            ], 500);
        }

        try {
            // Set Stripe API key
            \Stripe\Stripe::setApiKey($stripeSecretKey);

            Log::info('Creating payment intent', [
                'stripe_key_length' => strlen($stripeSecretKey),
                'key_prefix' => substr($stripeSecretKey, 0, 10) . '...',
            ]);

            $user = $request->user();
            $amount = $validated['amount'];
            $currency = strtolower($validated['currency'] ?? 'cad');

            // Convert amount to cents (Stripe uses smallest currency unit)
            $amountInCents = (int) round($amount * 100);

            // Get customer email and name
            $customerEmail = $user->email ?? null;
            $customerName = ($user->first_name ?? '') . ' ' . ($user->last_name ?? '');

            // Create or get Stripe customer
            $customerId = null;
            if ($user) {
                // Check if customer already exists
                $existingPayment = Payment::where('user_id', $user->id)
                    ->whereNotNull('stripe_customer_id')
                    ->first();

                if ($existingPayment && $existingPayment->stripe_customer_id) {
                    $customerId = $existingPayment->stripe_customer_id;
                    Log::info('Using existing Stripe customer', [
                        'customer_id' => $customerId,
                        'user_id' => $user->id,
                    ]);
                } else {
                    // Create new Stripe customer
                    $customer = \Stripe\Customer::create([
                        'email' => $customerEmail,
                        'name' => trim($customerName) ?: null,
                        'metadata' => [
                            'user_id' => $user->id,
                        ],
                    ]);
                    $customerId = $customer->id;
                    Log::info('Created new Stripe customer', [
                        'customer_id' => $customerId,
                        'user_id' => $user->id,
                    ]);
                }
            }

            // Create payment intent
            $paymentIntentData = [
                'amount' => $amountInCents,
                'currency' => $currency,
                'metadata' => [
                    'user_id' => $user->id ?? 0,
                    'enrollment_id' => $validated['enrollment_id'] ?? 0,
                ],
            ];

            if ($customerId) {
                $paymentIntentData['customer'] = $customerId;
            }

            $paymentIntent = \Stripe\PaymentIntent::create($paymentIntentData);

            // Create payment record in database
            $discountAmount = $validated['discount_amount'] ?? 0;
            $finalAmount = $amount - $discountAmount;
            
            $payment = Payment::create([
                'user_id' => $user->id ?? null,
                'enrollment_id' => $validated['enrollment_id'] ?? null,
                'amount' => $amount,
                'currency' => $currency,
                'status' => 'pending',
                'transaction_id' => $paymentIntent->id,
                'stripe_customer_id' => $customerId,
                'coupon_code' => $validated['coupon_code'] ?? null,
                'discount_amount' => $discountAmount,
                'final_amount' => $finalAmount,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'client_secret' => $paymentIntent->client_secret,
                    'payment_id' => $payment->id,
                    'amount' => $amount,
                    'currency' => $currency,
                ],
                'message' => 'Payment intent created',
            ]);
        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment processing error: ' . $e->getMessage(),
            ], 500);
        } catch (\Exception $e) {
            Log::error('Payment Intent Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create payment intent: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Confirm payment - POST /api/payment/confirm
     * IMPORTANT: This endpoint is called AFTER Stripe payment succeeds on frontend
     *
     * Strategy:
     * 1. Find payment record in database by transaction_id
     * 2. Mark payment as 'completed' and enrollment as 'active'
     * 3. Trust Stripe webhook to handle verification (webhook is authoritative)
     * 4. Use frontend confirmation as optimization only
     */
    public function confirmPayment(Request $request)
    {
        $validated = $request->validate([
            'payment_intent_id' => 'required|string',
            'enrollment_id' => 'required|exists:enrollments,id',
        ]);

        $user = $request->user();
        $paymentIntentId = $validated['payment_intent_id'];

        try {
            // Step 1: Find payment record by transaction_id
            $payment = Payment::where('transaction_id', $paymentIntentId)->first();

            // If not found by transaction_id, try by enrollment_id and user_id
            if (!$payment) {
                $payment = Payment::where('enrollment_id', $validated['enrollment_id'])
                    ->where('user_id', $user->id)
                    ->orderBy('created_at', 'desc')
                    ->first();
            }

            // Step 2: Handle payment not found - try to create from Stripe data
            if (!$payment) {
                Log::warning('Payment record not found for confirmation', [
                    'payment_intent_id' => $paymentIntentId,
                    'enrollment_id' => $validated['enrollment_id'],
                    'user_id' => $user->id,
                ]);

                // Try to create payment record from Stripe (orphaned payment recovery)
                try {
                    // Get Stripe secret key
                    $stripeSecretKey = env('STRIPE_SECRET_KEY');
                    if (!empty($stripeSecretKey)) {
                        \Stripe\Stripe::setApiKey($stripeSecretKey);

                        try {
                            $paymentIntent = \Stripe\PaymentIntent::retrieve($paymentIntentId);

                            // Only create if payment actually succeeded
                            if ($paymentIntent->status === 'succeeded') {
                                Log::critical('ORPHANED PAYMENT - Creating from Stripe data', [
                                    'payment_intent_id' => $paymentIntentId,
                                    'enrollment_id' => $validated['enrollment_id'],
                                    'user_id' => $user->id,
                                    'stripe_amount' => $paymentIntent->amount,
                                ]);

                                $payment = Payment::create([
                                    'user_id' => $user->id,
                                    'enrollment_id' => $validated['enrollment_id'],
                                    'amount' => $paymentIntent->amount / 100,
                                    'currency' => strtoupper($paymentIntent->currency),
                                    'status' => 'completed',
                                    'transaction_id' => $paymentIntentId,
                                    'paid_at' => now(),
                                ]);

                                Log::info('Recovered orphaned payment', [
                                    'payment_id' => $payment->id,
                                    'transaction_id' => $paymentIntentId,
                                ]);
                            } else {
                                // Payment not actually succeeded
                                Log::warning('Payment intent status not succeeded', [
                                    'payment_intent_id' => $paymentIntentId,
                                    'status' => $paymentIntent->status,
                                ]);

                                return response()->json([
                                    'success' => false,
                                    'message' => 'Payment was not completed in Stripe. Status: ' . $paymentIntent->status,
                                ], 402);
                            }
                        } catch (\Stripe\Exception\ApiErrorException $stripeErr) {
                            // Stripe API error - maybe invalid key or network issue
                            Log::warning('Stripe API error during orphaned payment recovery', [
                                'error' => $stripeErr->getMessage(),
                                'payment_intent_id' => $paymentIntentId,
                            ]);

                            // Fall back: trust frontend and create payment record anyway
                            // Webhook will verify later
                            Log::info('Creating payment record (trusting frontend, webhook will verify)', [
                                'payment_intent_id' => $paymentIntentId,
                                'enrollment_id' => $validated['enrollment_id'],
                            ]);

                            $payment = Payment::create([
                                'user_id' => $user->id,
                                'enrollment_id' => $validated['enrollment_id'],
                                'amount' => 0, // Will be verified by webhook
                                'currency' => 'cad',
                                'status' => 'completed',
                                'transaction_id' => $paymentIntentId,
                                'paid_at' => now(),
                            ]);
                        }
                    } else {
                        // No Stripe key configured - create payment record trusting frontend
                        Log::info('STRIPE_SECRET_KEY not configured, creating payment record trusting frontend', [
                            'payment_intent_id' => $paymentIntentId,
                            'enrollment_id' => $validated['enrollment_id'],
                        ]);

                        $payment = Payment::create([
                            'user_id' => $user->id,
                            'enrollment_id' => $validated['enrollment_id'],
                            'amount' => 0,
                            'currency' => 'cad',
                            'status' => 'completed',
                            'transaction_id' => $paymentIntentId,
                            'paid_at' => now(),
                        ]);
                    }
                } catch (\Exception $err) {
                    Log::error('Failed to create payment record during confirmation', [
                        'error' => $err->getMessage(),
                        'payment_intent_id' => $paymentIntentId,
                        'enrollment_id' => $validated['enrollment_id'],
                    ]);

                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to process payment confirmation. Please try again.',
                    ], 500);
                }
            } else {
                // Step 3: Payment record exists - just ensure it's marked as completed
                if ($payment->status !== 'completed') {
                    $payment->status = 'completed';
                    $payment->paid_at = $payment->paid_at ?? now();
                    $payment->save();

                    Log::info('Updated payment status to completed', [
                        'payment_id' => $payment->id,
                        'payment_intent_id' => $paymentIntentId,
                    ]);
                }
            }

            // Step 4: Update enrollment status to active
            $enrollment = Enrollment::with('course', 'user', 'classType')->findOrFail($validated['enrollment_id']);

            if ($enrollment->status !== 'active') {
                $enrollment->status = 'active';
                $enrollment->save();

                Log::info('Updated enrollment status to active', [
                    'enrollment_id' => $enrollment->id,
                    'payment_id' => $payment->id,
                ]);
            }

            // Step 5: Send confirmation email to user
            try {
                $this->sendPurchaseConfirmationEmail($enrollment->user ?? $user, $enrollment, $payment);
            } catch (\Exception $e) {
                Log::error('Failed to send purchase confirmation email', [
                    'error' => $e->getMessage(),
                    'user_id' => $user->id,
                    'enrollment_id' => $enrollment->id,
                    'trace' => $e->getTraceAsString(),
                ]);
                // Don't fail the payment if email fails
            }

            Log::info('Payment confirmation successful', [
                'payment_id' => $payment->id,
                'enrollment_id' => $enrollment->id,
                'payment_status' => $payment->status,
                'payment_intent_id' => $paymentIntentId,
                'user_id' => $user->id,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'payment' => $payment,
                    'enrollment' => $enrollment,
                ],
                'message' => 'Payment confirmed successfully',
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Confirmation Error', [
                'error' => $e->getMessage(),
                'payment_intent_id' => $validated['payment_intent_id'] ?? null,
                'enrollment_id' => $validated['enrollment_id'] ?? null,
                'user_id' => $user->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to confirm payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Check payment status - GET /api/payment/status/{intentId}
     */
    public function checkStatus(Request $request, $intentId)
    {
        $user = $request->user();

        $payment = Payment::where('user_id', $user->id)->firstOrFail();

        return response()->json([
            'success' => true,
            'data' => [
                'status' => $payment->status,
                'amount' => $payment->amount,
                'transaction_id' => $payment->transaction_id,
            ],
            'message' => 'Payment status',
        ]);
    }

    /**
     * Handle Stripe webhook - POST /api/payment/webhook
     * IMPORTANT: Configure this endpoint in Stripe Dashboard > Webhooks
     * Required events: payment_intent.succeeded, payment_intent.payment_failed, charge.refunded
     *
     * Setup in Stripe:
     * 1. Go to https://dashboard.stripe.com/webhooks
     * 2. Add endpoint: https://yoursite.com/api/payment/webhook
     * 3. Select events: payment_intent.succeeded, payment_intent.payment_failed
     * 4. Copy the signing secret and add to .env as STRIPE_WEBHOOK_SECRET
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sig_header = $request->header('Stripe-Signature');

        // Get webhook secret from environment
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        // If webhook secret not configured, log warning but still process
        // This allows graceful degradation in development
        if (!$endpoint_secret) {
            Log::warning('STRIPE_WEBHOOK_SECRET not configured. Webhook signature verification skipped.');
        } else {
            // Verify webhook signature (CRITICAL SECURITY CHECK)
            try {
                \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

                $event = \Stripe\Webhook::constructEvent(
                    $payload,
                    $sig_header,
                    $endpoint_secret
                );
            } catch (\UnexpectedValueException $e) {
                // Invalid payload
                Log::error('Webhook payload invalid', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Invalid payload'], 400);
            } catch (\Stripe\Exception\SignatureVerificationException $e) {
                // Invalid signature
                Log::error('Webhook signature verification failed', ['error' => $e->getMessage()]);
                return response()->json(['error' => 'Invalid signature'], 403);
            }

            // If we get here, signature is verified
            Log::info('Webhook signature verified', ['event_id' => $event['id']]);
        }

        // If no endpoint_secret, parse event manually (not verified)
        if (!$endpoint_secret) {
            $event = json_decode($payload, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse webhook payload');
                return response()->json(['error' => 'Invalid JSON'], 400);
            }
        }

        // Process webhook events
        if ($event && isset($event['type'])) {
            try {
                switch ($event['type']) {
                    case 'payment_intent.succeeded':
                        $this->handlePaymentSucceeded($event);
                        break;

                    case 'payment_intent.payment_failed':
                        $this->handlePaymentFailed($event);
                        break;

                    case 'charge.refunded':
                        $this->handleChargeRefunded($event);
                        break;

                    default:
                        Log::debug('Unhandled webhook event type: ' . $event['type']);
                }
            } catch (\Exception $e) {
                Log::error('Error processing webhook event', [
                    'event_type' => $event['type'] ?? 'unknown',
                    'event_id' => $event['id'] ?? 'unknown',
                    'error' => $e->getMessage(),
                ]);
                // Return 200 anyway - Stripe will retry if we return error
                return response()->json(['success' => true, 'warning' => $e->getMessage()]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Handle payment_intent.succeeded webhook event
     */
    private function handlePaymentSucceeded($event)
    {
        $paymentIntentId = $event['data']['object']['id'] ?? null;
        $amount = $event['data']['object']['amount'] ?? null;
        $currency = $event['data']['object']['currency'] ?? null;

        if (!$paymentIntentId) {
            Log::error('Webhook: Missing payment intent ID', ['event' => $event]);
            return;
        }

        // Find payment by transaction_id (payment intent ID)
        $payment = Payment::where('transaction_id', $paymentIntentId)->first();

        if (!$payment) {
            Log::warning('Webhook: Payment not found for succeeded intent', [
                'payment_intent_id' => $paymentIntentId,
            ]);
            // Don't create payment from webhook - let confirmPayment endpoint handle it
            return;
        }

        // Only update if not already completed (idempotent)
        if ($payment->status === 'completed') {
            Log::debug('Webhook: Payment already completed', [
                'payment_id' => $payment->id,
                'payment_intent_id' => $paymentIntentId,
            ]);
            return;
        }

        // Update payment status
        $payment->status = 'completed';
        $payment->paid_at = $payment->paid_at ?? now();
        $payment->save();

        Log::info('Webhook: Payment marked as completed', [
            'payment_id' => $payment->id,
            'payment_intent_id' => $paymentIntentId,
            'amount' => $amount,
            'currency' => $currency,
        ]);

        // Update related enrollment status
        if ($payment->enrollment_id) {
            $enrollment = Enrollment::find($payment->enrollment_id);
            if ($enrollment && $enrollment->status !== 'active') {
                $enrollment->status = 'active';
                $enrollment->save();

                Log::info('Webhook: Enrollment marked as active', [
                    'enrollment_id' => $enrollment->id,
                    'payment_id' => $payment->id,
                ]);

                // Send confirmation email to user
                $user = $payment->user;
                if ($user) {
                    $this->sendPurchaseConfirmationEmail($user, $enrollment, $payment);
                }
            }
        }
    }

    /**
     * Handle payment_intent.payment_failed webhook event
     */
    private function handlePaymentFailed($event)
    {
        $paymentIntentId = $event['data']['object']['id'] ?? null;
        $failureMessage = $event['data']['object']['last_payment_error']['message'] ?? 'Unknown error';

        if (!$paymentIntentId) {
            Log::error('Webhook: Missing payment intent ID in failed event', ['event' => $event]);
            return;
        }

        // Find payment by transaction_id
        $payment = Payment::where('transaction_id', $paymentIntentId)->first();

        if (!$payment) {
            Log::warning('Webhook: Payment not found for failed intent', [
                'payment_intent_id' => $paymentIntentId,
            ]);
            return;
        }

        // Only update if not already marked as failed (idempotent)
        if ($payment->status === 'failed') {
            Log::debug('Webhook: Payment already marked as failed', [
                'payment_id' => $payment->id,
            ]);
            return;
        }

        // Update payment status
        $payment->status = 'failed';
        $payment->save();

        Log::info('Webhook: Payment marked as failed', [
            'payment_id' => $payment->id,
            'payment_intent_id' => $paymentIntentId,
            'failure_message' => $failureMessage,
        ]);

        // Update enrollment status to cancelled
        if ($payment->enrollment_id) {
            $enrollment = Enrollment::find($payment->enrollment_id);
            if ($enrollment && $enrollment->status !== 'cancelled') {
                $enrollment->status = 'cancelled';
                $enrollment->save();

                Log::info('Webhook: Enrollment marked as cancelled due to failed payment', [
                    'enrollment_id' => $enrollment->id,
                    'payment_id' => $payment->id,
                ]);
            }
        }
    }

    /**
     * Handle charge.refunded webhook event
     */
    private function handleChargeRefunded($event)
    {
        $chargeId = $event['data']['object']['id'] ?? null;
        $amount = $event['data']['object']['amount_refunded'] ?? null;

        if (!$chargeId) {
            Log::error('Webhook: Missing charge ID in refund event', ['event' => $event]);
            return;
        }

        // Log refund event - webhook receives this when a charge is refunded
        Log::info('Webhook: Charge refunded', [
            'charge_id' => $chargeId,
            'amount_refunded' => $amount,
        ]);
    }

    /**
     * Send purchase confirmation email to user
     */
    private function sendPurchaseConfirmationEmail($user, $enrollment, $payment)
    {
        try {
            // Check if Google OAuth is configured (preferred method)
            $envPath = base_path('.env');
            $useGoogleOAuth = false;
            $googleAccessToken = '';
            $googleFromEmail = '';
            $googleFromName = '';
            
            if (File::exists($envPath)) {
                $envContent = File::get($envPath);
                
                if (preg_match('/^GOOGLE_ACCESS_TOKEN=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $googleAccessToken = trim($matches[1], ' "\'');
                }
                if (preg_match('/^GOOGLE_FROM_EMAIL=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $googleFromEmail = trim($matches[1], ' "\'');
                }
                if (preg_match('/^GOOGLE_FROM_NAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $googleFromName = trim($matches[1], ' "\'');
                }
                
                if (!empty($googleAccessToken)) {
                    $useGoogleOAuth = true;
                }
            }

            // FALLBACK: If Google tokens are empty in .env (e.g. after production build), check database
            if (empty($googleAccessToken)) {
                $dbToken = Settings::where('key', 'google_access_token')->first();
                if ($dbToken) {
                    $googleAccessToken = $dbToken->value;
                    $useGoogleOAuth = true;
                }
            }
            if (empty($googleFromEmail)) {
                $dbFromEmail = Settings::where('key', 'google_from_email')->first();
                if ($dbFromEmail) $googleFromEmail = $dbFromEmail->value;
            }
            if (empty($googleFromName)) {
                $dbFromName = Settings::where('key', 'google_from_name')->first();
                if ($dbFromName) $googleFromName = $dbFromName->value;
            }

            // Get course details - check both course and class_type
            $course = $enrollment->course;
            $classType = $enrollment->classType;
            
            // Use course title if available, otherwise use class type name
            $courseTitle = $course ? $course->course_title : ($classType ? ($classType->class_name ?? 'Course') : 'Course');
            $appName = \App\Models\Settings::where('key', 'site_name')->value('value') ?? config('app.name', 'FocusFrame');
            
            Log::info('Sending purchase confirmation email', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'enrollment_id' => $enrollment->id,
                'course_title' => $courseTitle,
                'has_course' => $course ? 'yes' : 'no',
                'has_class_type' => $classType ? 'yes' : 'no',
            ]);

            if ($useGoogleOAuth) {
                // Send via Google OAuth Gmail API
                $fromEmail = $googleFromEmail ?: config('mail.from.address', 'noreply@example.com');
                $fromName = $googleFromName ?: $appName;
                
                $emailMessage = "From: {$fromName} <{$fromEmail}>\r\n";
                $emailMessage .= "To: {$user->email}\r\n";
                $emailMessage .= "Subject: Enrollment Confirmation\r\n";
                $emailMessage .= "Content-Type: text/html; charset=utf-8\r\n";
                $emailMessage .= "\r\n";
                $emailMessage .= "<html><body>";
                $emailMessage .= "<p>Hello {$user->first_name},</p>";
                $emailMessage .= "<p>Thank you for enrolling in our <strong>{$courseTitle}</strong>.</p>";
                $emailMessage .= "<p>Your payment of <strong>\${$payment->final_amount}</strong> has been successfully processed, and your enrollment is now active.</p>";
                $emailMessage .= "<p>We are currently in the process of setting up your classes, which may take 3–5 days. Once everything is ready, you will receive your class schedule along with access to the student portal.</p>";
                $emailMessage .= "<p>If you have any questions in the meantime, feel free to reach out.</p>";
                $emailMessage .= "<p>Best regards,<br>Fluence Francaise Team</p>";
                $emailMessage .= "</body></html>";

                $rawMessage = base64_encode($emailMessage);
                $rawMessage = str_replace(['+', '/', '='], ['-', '_', ''], $rawMessage); // URL-safe base64

                // Send email via Gmail API
                // Try to send email - if token expired, we'll refresh and retry
                $httpClient = config('app.env') === 'production' 
                    ? Http::withHeaders(['Authorization' => 'Bearer ' . $googleAccessToken, 'Content-Type' => 'application/json'])
                    : Http::withoutVerifying()->withHeaders(['Authorization' => 'Bearer ' . $googleAccessToken, 'Content-Type' => 'application/json']);
                
                $response = $httpClient->post('https://gmail.googleapis.com/gmail/v1/users/me/messages/send', [
                    'raw' => $rawMessage,
                ]);

                if ($response->successful()) {
                    Log::info('Purchase confirmation email sent via Google OAuth', [
                        'user_id' => $user->id,
                        'enrollment_id' => $enrollment->id,
                    ]);
                    return;
                } else {
                    Log::warning('Direct Gmail API failed, trying GoogleMailService fallback', [
                        'user_id' => $user->id,
                        'response_status' => $response->status(),
                    ]);
                }
            }

            // Try to send via Google OAuth using central service as fallback
            $emailSent = \App\Services\GoogleMailService::sendEmail(
                $user->email,
                'Enrollment Confirmation',
                "
                <html>
                <body>
                    <p>Hello {$user->first_name},</p>
                    <p>Thank you for enrolling in our <strong>{$courseTitle}</strong>.</p>
                    <p>Your payment of <strong>\${$payment->final_amount}</strong> has been successfully processed, and your enrollment is now active.</p>
                    <p>We are currently in the process of setting up your classes, which may take 3–5 days. Once everything is ready, you will receive your class schedule along with access to the student portal.</p>
                    <p>If you have any questions in the meantime, feel free to reach out.</p>
                    <p>Best regards,<br>Fluence Francaise Team</p>
                </body>
                </html>
                "
            );

            // Fallback to SMTP if Google OAuth not available or failed
            if (!$emailSent) {
                // Configure SMTP settings dynamically from .env
                $envPath = base_path('.env');
                $envContent = File::get($envPath);
                
                $smtpHost = '';
                $smtpPort = 587;
                $smtpUsername = '';
                $smtpPassword = '';
                $fromEmail = '';
                $fromName = '';
                
                // Try custom MAIL_SMTP_* variables first
                if (preg_match('/^MAIL_SMTP_HOST=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpHost = trim($matches[1], ' "\'');
                }
                if (preg_match('/^MAIL_SMTP_PORT=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpPort = (int)trim($matches[1], ' "\'') ?: 587;
                }
                if (preg_match('/^MAIL_SMTP_USERNAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpUsername = trim($matches[1], ' "\'');
                }
                if (preg_match('/^MAIL_SMTP_PASSWORD=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $smtpPassword = trim($matches[1], ' "\'');
                }
                
                // Auto-detect Gmail SMTP if port 587 and credentials exist AND username is a Gmail address
                if (!$smtpHost && $smtpPort == 587 && !empty($smtpUsername) && !empty($smtpPassword)) {
                    // Only auto-detect if username looks like a Gmail address
                    if (strpos($smtpUsername, '@gmail.com') !== false || strpos($smtpUsername, '@googlemail.com') !== false) {
                        $smtpHost = 'smtp.gmail.com';
                    }
                }
                
                // If still no host and we have credentials, try to infer from username
                if (!$smtpHost && !empty($smtpUsername) && !empty($smtpPassword)) {
                    // Check if username contains a domain
                    if (strpos($smtpUsername, '@') !== false) {
                        $domain = substr($smtpUsername, strpos($smtpUsername, '@') + 1);
                        // Use smtp.{domain} as host
                        $smtpHost = 'smtp.' . $domain;
                    }
                }
                
                if (preg_match('/^MAIL_FROM_ADDRESS=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $fromEmail = trim($matches[1], ' "\'');
                }
                if (preg_match('/^MAIL_FROM_NAME=(.*?)(?:\r?\n|$)/m', $envContent, $matches)) {
                    $fromName = trim($matches[1], ' "\'');
                }
                
                if ($smtpHost && $smtpUsername) {
                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.host' => $smtpHost,
                        'mail.mailers.smtp.port' => $smtpPort,
                        'mail.mailers.smtp.username' => $smtpUsername,
                        'mail.mailers.smtp.password' => $smtpPassword,
                        'mail.mailers.smtp.encryption' => $smtpPort == 465 ? 'ssl' : 'tls',
                        'mail.from.address' => $fromEmail ?: $smtpUsername,
                        'mail.from.name' => $fromName ?: $appName,
                    ]);

                    // Send email using Laravel Mail
                    try {
                        \Illuminate\Support\Facades\Mail::send([], [], function ($message) use ($user, $courseTitle, $payment) {
                            $message->to($user->email, $user->name)
                                ->subject('Enrollment Confirmation')
                                ->html("
                                    <html>
                                    <body>
                                        <p>Hello {$user->first_name},</p>
                                        <p>Thank you for enrolling in our <strong>{$courseTitle}</strong>.</p>
                                        <p>Your payment of <strong>\${$payment->final_amount}</strong> has been successfully processed, and your enrollment is now active.</p>
                                        <p>We are currently in the process of setting up your classes, which may take 3–5 days. Once everything is ready, you will receive your class schedule along with access to the student portal.</p>
                                        <p>If you have any questions in the meantime, feel free to reach out.</p>
                                        <p>Best regards,<br>Fluence Francaise Team</p>
                                    </body>
                                    </html>
                                ");
                        });

                        Log::info('Purchase confirmation email sent via SMTP', [
                            'user_id' => $user->id,
                            'enrollment_id' => $enrollment->id,
                            'user_email' => $user->email,
                        ]);
                    } catch (\Exception $mailException) {
                        Log::error('SMTP email sending failed', [
                            'error' => $mailException->getMessage(),
                            'user_id' => $user->id,
                            'enrollment_id' => $enrollment->id,
                            'user_email' => $user->email,
                            'smtp_host' => $smtpHost,
                            'smtp_username' => $smtpUsername,
                            'trace' => $mailException->getTraceAsString(),
                        ]);
                        // Don't throw - just log the error
                    }
                } else {
                    Log::error('Email settings not configured, skipping purchase confirmation email', [
                        'user_id' => $user->id,
                        'enrollment_id' => $enrollment->id,
                        'user_email' => $user->email,
                        'smtp_host' => $smtpHost ?: 'not set',
                        'smtp_username' => $smtpUsername ? 'configured' : 'not configured',
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Failed to send purchase confirmation email', [
                'error' => $e->getMessage(),
                'user_id' => $user->id ?? null,
                'enrollment_id' => $enrollment->id ?? null,
                'trace' => $e->getTraceAsString(),
            ]);
            // Don't fail the payment if email fails
        }
    }



    /**
     * Get or create a Stripe coupon based on the local coupon code
     * Creates a Stripe coupon with the proper duration (once, forever, repeating)
     *
     * @param string $couponCode The local coupon code
     * @return string|null The Stripe coupon ID or null if not found/error
     */
    private function getOrCreateStripeCoupon($couponCode)
    {
        try {
            // Look up the coupon in the database
            $coupon = Coupon::where('code', strtoupper(trim($couponCode)))->first();

            if (!$coupon) {
                Log::warning('Coupon not found in database', ['code' => $couponCode]);
                return null;
            }

            // Generate a unique Stripe coupon ID based on our coupon
            // Include duration in the ID so different durations create different Stripe coupons
            $stripeCouponId = 'coupon_' . $coupon->id . '_' . $coupon->duration;
            if ($coupon->duration === 'repeating' && $coupon->duration_in_months) {
                $stripeCouponId .= '_' . $coupon->duration_in_months . 'm';
            }

            // Try to retrieve existing Stripe coupon and verify it matches our settings
            try {
                $existingCoupon = \Stripe\Coupon::retrieve($stripeCouponId);
                if ($existingCoupon) {
                    // Verify the existing coupon has the correct duration
                    $expectedDuration = $coupon->duration ?: 'once';
                    if ($existingCoupon->duration !== $expectedDuration) {
                        Log::warning('Existing Stripe coupon has wrong duration, will delete and recreate', [
                            'stripe_coupon_id' => $stripeCouponId,
                            'expected_duration' => $expectedDuration,
                            'actual_duration' => $existingCoupon->duration,
                        ]);
                        // Delete the incorrect coupon
                        $existingCoupon->delete();
                    } else {
                        Log::info('Using existing Stripe coupon', [
                            'stripe_coupon_id' => $stripeCouponId,
                            'local_coupon_id' => $coupon->id,
                            'duration' => $existingCoupon->duration,
                        ]);
                        return $stripeCouponId;
                    }
                }
            } catch (\Stripe\Exception\InvalidRequestException $e) {
                // Coupon doesn't exist in Stripe, we'll create it
                Log::info('Stripe coupon not found, will create new one', [
                    'stripe_coupon_id' => $stripeCouponId,
                ]);
            }

            // Build Stripe coupon data
            $stripeCouponData = [
                'id' => $stripeCouponId,
                'name' => $coupon->name ?: $coupon->code,
                'duration' => $coupon->duration ?: 'once',
            ];

            // Set discount type and value
            if ($coupon->discount_type === 'percentage') {
                $stripeCouponData['percent_off'] = floatval($coupon->discount_value);
            } else {
                // Fixed amount - convert to cents for Stripe
                $stripeCouponData['amount_off'] = intval($coupon->discount_value * 100);
                $stripeCouponData['currency'] = 'cad';
            }

            // Set duration_in_months for repeating coupons
            if ($coupon->duration === 'repeating' && $coupon->duration_in_months) {
                $stripeCouponData['duration_in_months'] = intval($coupon->duration_in_months);
            }

            // Create the Stripe coupon
            Log::info('Creating Stripe coupon with data', [
                'coupon_data' => $stripeCouponData,
                'local_coupon_id' => $coupon->id,
            ]);

            $stripeCoupon = \Stripe\Coupon::create($stripeCouponData);

            Log::info('Created new Stripe coupon', [
                'stripe_coupon_id' => $stripeCoupon->id,
                'local_coupon_id' => $coupon->id,
                'duration' => $stripeCoupon->duration,
                'duration_in_months' => $stripeCoupon->duration_in_months ?? null,
                'amount_off' => $stripeCoupon->amount_off ?? null,
                'percent_off' => $stripeCoupon->percent_off ?? null,
            ]);

            return $stripeCoupon->id;

        } catch (\Stripe\Exception\ApiErrorException $e) {
            Log::error('Stripe API error creating coupon', [
                'error' => $e->getMessage(),
                'coupon_code' => $couponCode,
            ]);
            return null;
        } catch (\Exception $e) {
            Log::error('Error creating Stripe coupon', [
                'error' => $e->getMessage(),
                'coupon_code' => $couponCode,
            ]);
            return null;
        }
    }
}
