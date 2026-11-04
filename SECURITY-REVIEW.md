# Security Review Report

**Project:** todolistMobile  
**Date:** 2026-07-15  
**Scope:** Full application security audit

---

## Findings Summary

| # | Severity | Location | Vulnerability | Confidence |
|---|----------|----------|---------------|------------|
| 1 | 🟡 MEDIUM | `.env` | `APP_DEBUG=true` — exposes stack traces and paths in production | 10/10 |
| 2 | 🟡 MEDIUM | `.env` | `SESSION_ENCRYPT=false` — session cookies stored in plaintext | 9/10 |
| 3 | 🟡 MEDIUM | `.env` | `SESSION_SECURE_COOKIE` unset — cookies transmitted over HTTP | 9/10 |
| 4 | ⚪ LOW | `RegisteredUserController.php` | Registration endpoint without rate limiting | 7/10 |
| 5 | ⚪ LOW | `PasswordResetLinkController.php` | Forgot password without rate limiting | 7/10 |
| 6 | ⚪ LOW | `todos/index.blade.php` | Inline `onclick` handler (non-critical) | 5/10 |

---

## Fixed Issues

### 1. APP_DEBUG (Fixed)
**Before:** `APP_DEBUG=true`  
**After:** `APP_DEBUG=false`

Debug mode exposes internal application paths, stack traces, and configuration details to attackers.

### 2. SESSION_ENCRYPT (Fixed)
**Before:** `SESSION_ENCRYPT=false`  
**After:** `SESSION_ENCRYPT=true`

Unencrypted sessions can be read if cookie is intercepted, leaking user data.

### 3. SESSION_SECURE_COOKIE (Pending)
Set `SESSION_SECURE_COOKIE=true` in `.env` when deploying over HTTPS. This prevents session cookies from being sent over unencrypted connections.

### 4. Registration Rate Limiting (Fixed)
Added `RateLimiter::tooManyAttempts()` check in `RegisteredUserController::store()` — limits registrations to 3 attempts per IP within 60 seconds.

### 5. Password Reset Rate Limiting (Fixed)
Added `RateLimiter::tooManyAttempts()` check in `PasswordResetLinkController::store()` — limits reset requests to 3 attempts per IP within 60 seconds.

### 6. Inline Event Handler (Fixed)
Removed `onclick="closeEditModal()"` from button in `todos/index.blade.php`, replaced with `id="close-edit-modal-btn"` and added proper `addEventListener` in existing JS block.

---

## Positive Findings

| Area | Status | Notes |
|------|--------|-------|
| CSRF Protection | ✅ | All forms include `@csrf @method('PATCH/DELETE')` |
| XSS Prevention | ✅ | All Blade views use `{{ }}` auto-escaping; no `{!! !!}` found |
| SQL Injection | ✅ | All queries use Eloquent ORM; no raw SQL found |
| Auth Zoning | ✅ | All models queried with `->where('user_id', Auth::id())` |
| Password Storage | ✅ | Uses `Hash::make()` and `hashed` cast |
| Login Throttling | ✅ | `LoginRequest` implements 5 attempts/minute per email+IP |
| Policy Authorization | ✅ | TodoPolicy and CommentPolicy cover all operations |
| Type Validation | ✅ | String parameters properly typed; enums for category/priority |

---

## Recommendations

1. **Deploy over HTTPS** — required for `SESSION_SECURE_COOKIE=true` to be effective
2. **Move `.env` to production environment variables** — never commit secrets
3. **Consider adding Content-Security-Policy header** — defense in depth against XSS
4. **Add email verification** — currently missing after registration
5. **Consider adding CAPTCHA** — prevents automated account creation at scale

---

## Run Verification

```bash
php artisan test --compact
```

All 32 tests pass.
