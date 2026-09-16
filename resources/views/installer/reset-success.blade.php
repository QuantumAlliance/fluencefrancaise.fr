<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Successful - FocusFrame</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
            padding: 60px 40px;
            max-width: 480px;
            text-align: center;
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success-icon {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            border-radius: 50%;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 30px;
            font-size: 50px;
            animation: scaleIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
            box-shadow: 0 10px 30px rgba(16, 185, 129, 0.3);
        }

        @keyframes scaleIn {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        h1 {
            color: #1f2937;
            margin-bottom: 12px;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 32px;
            line-height: 1.6;
        }

        .timer-section {
            background: linear-gradient(135deg, #f0fdf4 0%, #f0fdfa 100%);
            border: 1px solid #dcfce7;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 32px;
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.3s both;
        }

        .timer-label {
            color: #6b7280;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 12px;
            letter-spacing: 0.3px;
            text-transform: uppercase;
        }

        .countdown {
            font-size: 56px;
            font-weight: 700;
            color: #10b981;
            font-variant-numeric: tabular-nums;
            letter-spacing: 2px;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 12px;
            animation: slideUp 0.6s cubic-bezier(0.34, 1.56, 0.64, 1) 0.4s both;
        }

        button {
            padding: 14px 24px;
            border: none;
            border-radius: 12px;
            font-size: 15px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            letter-spacing: 0.3px;
        }

        .btn-primary {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            box-shadow: 0 10px 25px rgba(16, 185, 129, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 35px rgba(16, 185, 129, 0.4);
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .btn-secondary {
            background: #f3f4f6;
            color: #4b5563;
            border: 1px solid #e5e7eb;
        }

        .btn-secondary:hover {
            background: #e5e7eb;
            transform: translateY(-2px);
        }

        .btn-secondary:active {
            transform: translateY(0);
        }

        .helper-text {
            color: #9ca3af;
            font-size: 13px;
            margin-top: 20px;
            line-height: 1.5;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1>Reset Successful!</h1>
        <p class="subtitle">
            Your database has been completely wiped and the installer has been reset. You can now start the setup process again.
        </p>

        <div class="timer-section">
            <div class="timer-label">Redirecting in</div>
            <div class="countdown" id="countdown">5</div>
        </div>

        <div class="action-buttons">
            <button class="btn-primary" onclick="window.location.href='/installer'">
                Go to Installer Now
            </button>
            <button class="btn-secondary" onclick="window.history.back()">
                Go Back
            </button>
        </div>

        <p class="helper-text">
            If you are not redirected automatically, click the button above.
        </p>
    </div>

    <script>
        let seconds = 5;
        const countdownElement = document.getElementById('countdown');

        const interval = setInterval(() => {
            seconds--;
            countdownElement.textContent = seconds;

            if (seconds <= 0) {
                clearInterval(interval);
                window.location.href = '/installer';
            }
        }, 1000);
    </script>
</body>
</html>
