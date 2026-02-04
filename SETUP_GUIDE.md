# Password Reset System - Quick Setup Guide

## ✅ Files Created

1. **Controller**: `app/Controllers/PasswordResetController.php`
2. **View**: `app/Views/Template/ForgotPassword.php`
3. **Documentation**: `PASSWORD_RESET_README.md`

## ✅ Files Modified

1. **Routes**: `app/Config/Routes.php` - Added PasswordReset routes
2. **Login Page**: `app/Views/LoginPage.php` - Updated "Forgot password?" link

## 🚀 Quick Start

### Access the Password Reset Page:
```
http://localhost/EngBakery/public/PasswordReset
```

Or click **"Forgot password?"** on the login page.

## ⚙️ Email Configuration

### Option 1: Use PHP mail() (Default - Already Configured)
No additional setup needed. Works on most servers with sendmail configured.

### Option 2: Use SMTP (Recommended for Production)

Edit `app/Config/Email.php`:

```php
public string $protocol = 'smtp';
public string $SMTPHost = 'smtp.gmail.com';
public string $SMTPUser = 'your-email@gmail.com';
public string $SMTPPass = 'your-app-password';  // Use App Password for Gmail
public int $SMTPPort = 587;
public string $SMTPCrypto = 'tls';
public string $mailType = 'html';
```

### Gmail Setup:
1. Go to: https://myaccount.google.com/apppasswords
2. Generate an "App Password"
3. Use that password in `$SMTPPass`

## 🧪 Testing

### Test with Console Logging (Development):

Temporarily add this line in `PasswordResetController.php` at line 58 (inside `requestOTP()` method):

```php
log_message('info', '🔐 TEST OTP for ' . $email . ': ' . $otp);
```

Then check the log file:
```
writable/logs/log-2026-02-04.php
```

### Test Flow:
1. Go to `/PasswordReset`
2. Enter a registered email (e.g., `jane.doe@example.com`)
3. Click "Send Verification Code"
4. Check email OR check logs for OTP
5. Enter the 6-digit code
6. Enter new password
7. Login with new password

## 📋 Features

✅ **3-Step Process**:
- Step 1: Enter email
- Step 2: Verify OTP (6-digit code)
- Step 3: Reset password

✅ **Security**:
- OTP expires in 3 minutes
- Session-based storage (no database changes)
- Password hashed with bcrypt
- 30-second resend cooldown

✅ **User Experience**:
- Real-time countdown timer
- Resend OTP functionality
- Mobile-responsive design
- Clear error messages

## 🔒 Security Notes

### Session Variables Used:
```
password_reset_email      → User's email
password_reset_otp        → 6-digit code
password_reset_otp_expiry → Expiration timestamp
password_reset_verified   → Verification status
```

### No Database Changes:
✅ No new tables
✅ No new columns
✅ Compatible with current database structure

## 📝 Routes Added

```php
GET  /PasswordReset                    → Display forgot password page
POST /PasswordReset/RequestOTP         → Send OTP to email
POST /PasswordReset/VerifyOTP          → Verify the OTP code
POST /PasswordReset/ResetPassword      → Reset the password
```

## 🐛 Troubleshooting

### Email Not Sending?
1. Check `writable/logs/log-*.php` for errors
2. Verify email exists in database
3. Check spam/junk folder
4. Try SMTP configuration

### OTP Expired?
- OTP is valid for exactly 3 minutes
- Click "Resend Code" to get a new one

### Can't Access Page?
- Clear browser cache
- Check Routes.php is saved
- Verify file permissions

## 🎯 Next Steps

### For Development:
1. Test with a real email address
2. Verify OTP emails arrive correctly
3. Test complete reset flow

### For Production:
1. Configure SMTP with secure credentials
2. Remove test logging code
3. Enable HTTPS
4. Set up monitoring/alerts
5. Consider adding CAPTCHA

## 📚 Full Documentation

See `PASSWORD_RESET_README.md` for:
- Detailed technical documentation
- Security recommendations
- Customization options
- Advanced troubleshooting
- Production checklist

## ✨ Summary

The password reset system is **ready to use** with:
- ✅ No database modifications
- ✅ Session-based OTP storage
- ✅ 3-minute OTP expiration
- ✅ Email integration with CI4
- ✅ User-friendly interface
- ✅ Secure password hashing

**Access it at**: `http://your-domain/PasswordReset`
