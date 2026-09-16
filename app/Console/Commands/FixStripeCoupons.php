<?php

namespace App\Console\Commands;

use App\Models\Coupon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class FixStripeCoupons extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stripe:fix-coupons {--dry-run : Run without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fix existing Stripe coupons to ensure they have the correct duration setting';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('Running in DRY RUN mode - no changes will be made');
        }

        // Set Stripe API key
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

        // Get all coupons from database
        $coupons = Coupon::all();

        $this->info("Found {$coupons->count()} coupons in database");
        $this->newLine();

        $fixed = 0;
        $errors = 0;

        foreach ($coupons as $coupon) {
            $this->line("Checking coupon: {$coupon->code} (ID: {$coupon->id})");

            // Generate Stripe coupon ID
            $stripeCouponId = 'coupon_' . $coupon->id . '_' . ($coupon->duration ?: 'once');
            if ($coupon->duration === 'repeating' && $coupon->duration_in_months) {
                $stripeCouponId .= '_' . $coupon->duration_in_months . 'm';
            }

            try {
                // Try to retrieve existing Stripe coupon
                $existingCoupon = \Stripe\Coupon::retrieve($stripeCouponId);

                if ($existingCoupon) {
                    $expectedDuration = $coupon->duration ?: 'once';

                    $this->line("  Stripe coupon ID: {$stripeCouponId}");
                    $this->line("  Expected duration: {$expectedDuration}");
                    $this->line("  Actual duration: {$existingCoupon->duration}");

                    if ($existingCoupon->duration !== $expectedDuration) {
                        $this->warn("  ⚠ Duration mismatch detected!");

                        if (!$dryRun) {
                            // Delete the incorrect coupon
                            $existingCoupon->delete();
                            $this->info("  ✓ Deleted incorrect Stripe coupon");

                            // Create new coupon with correct settings
                            $stripeCouponData = [
                                'id' => $stripeCouponId,
                                'name' => $coupon->name ?: $coupon->code,
                                'duration' => $expectedDuration,
                            ];

                            // Set discount type and value
                            if ($coupon->discount_type === 'percentage') {
                                $stripeCouponData['percent_off'] = floatval($coupon->discount_value);
                            } else {
                                $stripeCouponData['amount_off'] = intval($coupon->discount_value * 100);
                                $stripeCouponData['currency'] = 'cad';
                            }

                            // Set duration_in_months for repeating coupons
                            if ($coupon->duration === 'repeating' && $coupon->duration_in_months) {
                                $stripeCouponData['duration_in_months'] = intval($coupon->duration_in_months);
                            }

                            $newCoupon = \Stripe\Coupon::create($stripeCouponData);
                            $this->info("  ✓ Created new Stripe coupon with correct duration");
                            $fixed++;
                        } else {
                            $this->comment("  [DRY RUN] Would delete and recreate coupon");
                            $fixed++;
                        }
                    } else {
                        $this->info("  ✓ Coupon duration is correct");
                    }
                } else {
                    $this->comment("  Stripe coupon not found (will be created on next use)");
                }

            } catch (\Stripe\Exception\InvalidRequestException $e) {
                $this->comment("  Stripe coupon not found (will be created on next use)");
            } catch (\Exception $e) {
                $this->error("  ✗ Error: " . $e->getMessage());
                $errors++;
            }

            $this->newLine();
        }

        $this->newLine();
        $this->info("Summary:");
        $this->line("  Total coupons checked: {$coupons->count()}");
        $this->line("  Coupons fixed: {$fixed}");
        $this->line("  Errors: {$errors}");

        if ($dryRun && $fixed > 0) {
            $this->newLine();
            $this->comment("Run without --dry-run flag to apply changes");
        }

        return 0;
    }
}
