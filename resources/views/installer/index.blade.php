<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Installation Wizard - FocusFrame</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-slide-in {
            animation: slideIn 0.3s ease-out;
        }

        .error-message {
            animation: slideIn 0.3s ease-out;
        }

        input:invalid:not(:placeholder-shown) {
            border-color: #ef4444;
        }

        input:valid:not(:placeholder-shown) {
            border-color: #10b981;
        }

        .input-valid {
            border-color: #10b981 !important;
        }

        .input-invalid {
            border-color: #ef4444 !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <div class="min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8 animate-slide-in">
                <div class="inline-block bg-white rounded-full p-3 mb-4 shadow-lg">
                    <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-gray-900">FocusFrame</h1>
                <p class="mt-2 text-lg text-gray-600">Installation Wizard</p>
                <p class="mt-1 text-sm text-gray-500">Follow the steps to set up your application</p>
            </div>

            <!-- Progress Steps -->
            <div class="mb-8">
                <div class="flex items-center justify-between text-xs">
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-blue-600 text-white font-bold step-1">1</div>
                            <div class="ml-2">
                                <p class="text-sm font-medium text-gray-900">Database</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 mx-1">
                        <div class="h-1 bg-gray-200 rounded"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-300 text-gray-600 font-bold step-2">2</div>
                            <div class="ml-2">
                                <p class="text-sm font-medium text-gray-600">Admin</p>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 mx-1">
                        <div class="h-1 bg-gray-200 rounded"></div>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full bg-gray-300 text-gray-600 font-bold step-3">3</div>
                            <div class="ml-2">
                                <p class="text-sm font-medium text-gray-600">Migrate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Container -->
            <div class="bg-white rounded-xl shadow-2xl px-6 py-8 backdrop-blur-sm animate-slide-in">
                <!-- Step 1: Database Configuration -->
                <div id="step-1" class="step-content">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Database Configuration</h2>

                    <form id="databaseForm" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Application Name</label>
                            <input type="text" id="app_name" name="app_name" placeholder="e.g., FocusFrame"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Type</label>
                            <select id="db_connection" name="db_connection"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none">
                                <option value="mysql">MySQL</option>
                                <option value="sqlite">SQLite</option>
                            </select>
                        </div>

                        <!-- MySQL Fields -->
                        <div id="mysql-fields">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Host</label>
                                <input type="text" id="db_host" name="db_host" placeholder="localhost" value="localhost"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"/>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Port</label>
                                <input type="number" id="db_port" name="db_port" placeholder="3306" value="3306"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"/>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Username</label>
                                <input type="text" id="db_username" name="db_username" placeholder="root" value="root"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"/>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Database Password</label>
                                <input type="password" id="db_password" name="db_password" placeholder="Leave blank if no password"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none"/>
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Database Name</label>
                            <input type="text" id="db_name" name="db_name" placeholder="focusframe_db"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required/>
                        </div>

                        <div id="errorMessage" class="mt-4 p-4 bg-red-50 border border-red-300 rounded-lg text-red-700 text-sm hidden error-message flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div id="errorMessageText"></div>
                        </div>

                        <button type="submit" id="submitBtn"
                                class="w-full mt-6 px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 active:bg-blue-800 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                            Save & Continue
                        </button>
                    </form>
                </div>

                <!-- Step 2: Super Admin Setup -->
                <div id="step-2" class="step-content hidden">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Super Admin Account</h2>

                    <form id="adminForm" class="space-y-4">
                        <p class="text-gray-600 text-sm mb-4">Create your admin account to manage the application</p>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
                            <input type="text" id="admin_first_name" name="first_name" placeholder="e.g., John"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
                            <input type="text" id="admin_last_name" name="last_name" placeholder="e.g., Doe"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                            <input type="email" id="admin_email" name="email" placeholder="admin@example.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                            <input type="password" id="admin_password" name="password" placeholder="Enter a strong password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required/>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                            <input type="password" id="admin_password_confirm" name="password_confirmation" placeholder="Confirm password"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none" required/>
                        </div>

                        <div id="adminErrorMessage" class="mt-4 p-4 bg-red-50 border border-red-300 rounded-lg text-red-700 text-sm hidden error-message flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div id="adminErrorMessageText"></div>
                        </div>

                        <button type="submit" id="adminSubmitBtn"
                                class="w-full mt-6 px-4 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed">
                            Create Admin & Continue
                        </button>
                    </form>
                </div>

                <!-- Step 3: Migration -->
                <div id="step-3" class="step-content hidden">
                    <h2 class="text-xl font-semibold text-gray-900 mb-6">Database Migrations</h2>

                    <div class="space-y-4">
                        <p class="text-gray-600">Click the button below to run database migrations and complete the setup.</p>

                        <div id="migrationStatus" class="p-3 bg-blue-50 border border-blue-200 rounded text-blue-700 text-sm hidden">
                            Running migrations...
                        </div>

                        <div id="migrationSuccess" class="p-3 bg-green-50 border border-green-200 rounded text-green-700 text-sm hidden">
                            Migrations completed successfully! Redirecting...
                        </div>

                        <div id="migrationError" class="p-4 bg-red-50 border border-red-300 rounded-lg text-red-700 text-sm hidden error-message flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div id="migrationErrorText"></div>
                        </div>

                        <button type="button" id="migrateBtn"
                                class="w-full mt-6 px-4 py-3 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700 active:bg-green-800 transition disabled:opacity-50 disabled:cursor-not-allowed shadow-md hover:shadow-lg">
                            Run Migrations
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const form = document.getElementById('databaseForm');
        const submitBtn = document.getElementById('submitBtn');
        const errorMessage = document.getElementById('errorMessage');
        const dbConnectionSelect = document.getElementById('db_connection');
        const mysqlFields = document.getElementById('mysql-fields');

        // Toggle MySQL fields
        dbConnectionSelect.addEventListener('change', function() {
            mysqlFields.style.display = this.value === 'mysql' ? 'block' : 'none';
        });

        // Form submission
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            submitBtn.disabled = true;
            submitBtn.textContent = 'Saving...';
            errorMessage.classList.add('hidden');

            const data = {
                app_name: document.getElementById('app_name').value,
                db_connection: document.getElementById('db_connection').value,
                db_host: document.getElementById('db_host').value,
                db_port: document.getElementById('db_port').value,
                db_username: document.getElementById('db_username').value,
                db_password: document.getElementById('db_password').value,
                db_name: document.getElementById('db_name').value,
            };

            try {
                const response = await fetch('/api/installer/save-database', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('step-1').classList.add('hidden');
                    document.getElementById('step-2').classList.remove('hidden');
                    document.querySelector('.step-1').classList.add('bg-green-600');
                    document.querySelector('.step-2').classList.add('bg-blue-600');
                } else {
                    throw new Error(result.message || 'Database connection failed');
                }
            } catch (error) {
                document.getElementById('errorMessageText').textContent = error.message;
                errorMessage.classList.remove('hidden');
                window.scrollTo({ top: errorMessage.offsetTop - 100, behavior: 'smooth' });
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Save & Continue';
            }
        });

        // Admin form submission
        const adminForm = document.getElementById('adminForm');
        const adminSubmitBtn = document.getElementById('adminSubmitBtn');
        const adminErrorMessage = document.getElementById('adminErrorMessage');

        adminForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            adminSubmitBtn.disabled = true;
            adminSubmitBtn.textContent = 'Creating...';
            adminErrorMessage.classList.add('hidden');

            const password = document.getElementById('admin_password').value;
            const passwordConfirm = document.getElementById('admin_password_confirm').value;

            if (password !== passwordConfirm) {
                document.getElementById('adminErrorMessageText').textContent = 'Passwords do not match';
                adminErrorMessage.classList.remove('hidden');
                adminSubmitBtn.disabled = false;
                adminSubmitBtn.textContent = 'Create Admin & Continue';
                return;
            }

            const data = {
                first_name: document.getElementById('admin_first_name').value,
                last_name: document.getElementById('admin_last_name').value,
                email: document.getElementById('admin_email').value,
                password: password,
                password_confirmation: passwordConfirm,
            };

            try {
                const response = await fetch('/api/installer/create-admin', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    document.getElementById('step-2').classList.add('hidden');
                    document.getElementById('step-3').classList.remove('hidden');
                    document.querySelector('.step-2').classList.add('bg-green-600');
                    document.querySelector('.step-3').classList.add('bg-blue-600');
                } else {
                    throw new Error(result.message || 'Admin creation failed');
                }
            } catch (error) {
                document.getElementById('adminErrorMessageText').textContent = error.message;
                adminErrorMessage.classList.remove('hidden');
                window.scrollTo({ top: adminErrorMessage.offsetTop - 100, behavior: 'smooth' });
            } finally {
                adminSubmitBtn.disabled = false;
                adminSubmitBtn.textContent = 'Create Admin & Continue';
            }
        });

        // Migration button
        document.getElementById('migrateBtn').addEventListener('click', async function() {
            const migrateBtn = this;
            const migrationStatus = document.getElementById('migrationStatus');
            const migrationSuccess = document.getElementById('migrationSuccess');
            const migrationError = document.getElementById('migrationError');

            migrateBtn.disabled = true;
            migrationStatus.classList.remove('hidden');
            migrationError.classList.add('hidden');

            try {
                const response = await fetch('/api/installer/migrate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                const result = await response.json();

                if (result.success) {
                    migrationStatus.classList.add('hidden');
                    migrationSuccess.classList.remove('hidden');
                    setTimeout(() => window.location.href = '/', 2000);
                } else {
                    throw new Error(result.message || 'Migration failed');
                }
            } catch (error) {
                migrationStatus.classList.add('hidden');
                document.getElementById('migrationErrorText').textContent = error.message;
                migrationError.classList.remove('hidden');
                migrateBtn.disabled = false;
            }
        });
    </script>
</body>
</html>
