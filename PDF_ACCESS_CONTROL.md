# PDF Upload Access Control

## Overview

PDF upload and question generation functionality is now restricted to **Admin** and **Teacher** roles only. Students and parents cannot access these features.

## Access Control Implementation

### 1. Middleware Protection

**TeacherAdminMiddleware** (`app/Http/Middleware/TeacherAdminMiddleware.php`)
- Allows access only to users with `admin` or `teacher` roles
- Redirects unauthorized users with appropriate error messages
- Maintains session compatibility

### 2. Route Protection

All PDF-related routes are protected:

```php
// PDF Upload Routes (Admin & Teacher Only)
Route::middleware(['teacher.admin'])->group(function () {
    Route::get('/pdf-upload', ...);
    Route::post('/pdf-upload', ...);
    Route::get('/pdf-upload/list', ...);
    // ... other PDF routes
});

// Question Generator API Routes (Admin & Teacher Only)
Route::middleware(['teacher.admin'])->prefix('api')->group(function () {
    Route::post('/question-generator/test', ...);
    Route::post('/question-generator/generate', ...);
    // ... other API routes
});
```

### 3. UI Access Control

**Admin Sidebar** (`resources/views/layouts/admin.blade.php`)
- PDF Upload links hidden for students and parents
- AI Tools section only visible to admin and teacher roles

```blade
@if(in_array(session('user.role'), ['admin', 'teacher']))
    <!-- PDF Upload and AI Tools links -->
@endif
```

### 4. Controller Logic

**PdfUploadController** prioritizes admin and teacher users:
- Session validation ensures appropriate role
- Automatic fallback to admin/teacher users
- Creates admin user if none exist

## Role-Based Access

### ✅ **ALLOWED ROLES**

#### **Admin Users**
- Full access to PDF upload
- Access to all question generation features
- Can use both Local and Groq AI generators
- Can manage all uploaded PDFs

#### **Teacher Users**
- Full access to PDF upload
- Access to all question generation features
- Can use both Local and Groq AI generators
- Can manage their uploaded PDFs

### ❌ **BLOCKED ROLES**

#### **Student Users**
- Cannot access PDF upload pages
- Cannot use question generation APIs
- Redirected with "Access denied" message
- PDF upload links hidden in UI

#### **Parent Users**
- Cannot access PDF upload pages
- Cannot use question generation APIs
- Redirected with "Access denied" message
- PDF upload links hidden in UI

## User Experience

### For Admin/Teacher Users
1. **Full Access**: See all PDF upload options in sidebar
2. **Question Generation**: Can upload PDFs and generate questions
3. **Generator Choice**: Can choose between Local and Groq AI
4. **PDF Management**: Can view, download, and delete PDFs

### For Student/Parent Users
1. **Hidden Features**: PDF upload links not visible in sidebar
2. **Access Denied**: Redirected if they try to access URLs directly
3. **Clear Message**: "Access denied. This feature is only available to administrators and teachers."
4. **Graceful Fallback**: Redirected to their dashboard

## Security Features

### Route-Level Protection
- Middleware checks role before allowing access
- Prevents direct URL access attempts
- Consistent across all PDF-related endpoints

### UI-Level Protection
- Conditional rendering based on user role
- Links hidden from unauthorized users
- Clean interface without inaccessible options

### Session Validation
- Ensures user has appropriate role
- Automatic session correction if needed
- Maintains security across requests

### Error Handling
- Graceful error messages for unauthorized access
- Proper redirects to appropriate pages
- No exposure of sensitive functionality

## Database Statistics

Current user distribution:
- **Admin**: 8 users ✅ (Can access PDF upload)
- **Teacher**: 5 users ✅ (Can access PDF upload)
- **Student**: 7 users ❌ (Blocked from PDF upload)
- **Parent**: 9 users ❌ (Blocked from PDF upload)

## Implementation Files

### New Files
- `app/Http/Middleware/TeacherAdminMiddleware.php` - Role-based access control

### Modified Files
- `bootstrap/app.php` - Middleware registration
- `routes/web.php` - Route protection
- `resources/views/layouts/admin.blade.php` - UI access control
- `app/Http/Controllers/PdfUploadController.php` - Enhanced user validation

## Testing Access Control

### Admin/Teacher Access
1. Login as admin or teacher
2. Navigate to admin panel
3. See "AI Tools" section with PDF upload links
4. Can access `/pdf-upload` successfully

### Student/Parent Access
1. Login as student or parent
2. Navigate to admin panel
3. "AI Tools" section is hidden
4. Direct access to `/pdf-upload` shows "Access denied"

## Error Messages

### Unauthorized Access
```
"Access denied. This feature is only available to administrators and teachers."
```

### Authentication Required
```
"Please login to access this feature."
```

### Account Deactivated
```
"Your account has been deactivated."
```

## Benefits

### Educational Appropriateness
- Teachers can create questions for their classes
- Admins can manage educational content
- Students focus on learning, not content creation

### Security
- Prevents unauthorized content generation
- Protects AI resources from misuse
- Maintains proper role separation

### User Experience
- Clean interface for each role
- No confusing inaccessible features
- Appropriate functionality for each user type

### Resource Management
- Controls access to AI generation features
- Prevents overuse by limiting to educators
- Maintains system performance

## Future Enhancements

### Possible Additions
- **Class-based permissions**: Teachers only see their class PDFs
- **Subject restrictions**: Limit teachers to their subject areas
- **Usage quotas**: Limit number of PDFs per teacher
- **Approval workflow**: Admin approval for teacher uploads

### Monitoring
- Track PDF upload usage by role
- Monitor question generation patterns
- Analyze feature adoption by educators
