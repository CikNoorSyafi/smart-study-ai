# Database Connection Fix - MySQL Setup

## 🚨 **CURRENT ERROR:**
```
SQLSTATE[HY000] [2002] No connection could be made because the target machine actively refused it
```

## **🎯 SOLUTION: Start MySQL Service**

### **Option 1: Using XAMPP Control Panel (Recommended)**
1. **Open XAMPP Control Panel**
2. **Start MySQL** service by clicking "Start" button
3. **Verify** MySQL is running (should show "Running" status)

### **Option 2: Using Command Line**
Try these commands in Command Prompt (as Administrator):
```cmd
net start mysql
```
or
```cmd
net start mysql80
```
or
```cmd
sc start mysql
```

### **Option 3: Manual MySQL Start**
1. **Open Services** (Windows + R, type `services.msc`)
2. **Find MySQL** service
3. **Right-click** → **Start**

## **🔧 ALTERNATIVE: Use SQLite (If MySQL Issues Persist)**

If MySQL continues to have issues, switch to SQLite:

### **Step 1: Update .env**
```env
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=smart_study_ai
# DB_USERNAME=root
# DB_PASSWORD=
```

### **Step 2: Create SQLite Database**
```cmd
php -r "touch('database/database.sqlite');"
```

### **Step 3: Install SQLite Extension**
If you get "could not find driver" error:
1. **Edit php.ini** file
2. **Uncomment** line: `;extension=sqlite3`
3. **Remove semicolon** to make it: `extension=sqlite3`
4. **Restart** web server

## **🗄️ DATABASE SETUP COMMANDS:**

### **After MySQL is Running:**
```cmd
php artisan config:clear
php artisan migrate
```

### **If Database Doesn't Exist:**
```cmd
mysql -u root -p
CREATE DATABASE smart_study_ai;
exit
php artisan migrate
```

## **✅ VERIFICATION:**

### **Test Database Connection:**
```cmd
php artisan tinker
DB::connection()->getPdo();
```

### **Check Tables:**
```cmd
php artisan migrate:status
```

## **🎯 QUICK FIX STEPS:**

1. **Start XAMPP** Control Panel
2. **Click "Start"** next to MySQL
3. **Wait** for MySQL to show "Running"
4. **Run**: `php artisan config:clear`
5. **Run**: `php artisan migrate`
6. **Test** Google OAuth again

## **📋 CURRENT DATABASE CONFIG:**
- **Connection**: MySQL
- **Host**: 127.0.0.1
- **Port**: 3306
- **Database**: smart_study_ai
- **Username**: root
- **Password**: (empty)

**Once MySQL is running, the Google OAuth should work without database connection errors! 🎯🔐✨**
