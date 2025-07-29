# 📧 Smiling Foundation - Complete Email System Documentation

## 🎯 Overview

The Smiling Foundation website features a comprehensive automated email system that handles multiple types of email communications across different user interactions. This system ensures professional, timely, and reliable email delivery for all stakeholders.

## 🏗️ System Architecture

### Core Components

1. **SMTPEmailHandler.php** - Main email handling class with custom SMTP implementation
2. **EmailHandler.php** - Simplified email handler (fallback)
3. **pages/receipt.php** - Web-based receipt page for donation receipts
4. **email_config.php** - Configuration management and environment variables

### Email Types Supported

1. **Donation Invoices** - Automatic receipts with download links
2. **Volunteer Approvals** - Welcome emails for approved volunteers
3. **Contact Form Notifications** - Admin notifications for new inquiries

---

## 📋 Email System Components

### 1. Donation Email System

**Purpose**: Automatically send professional invoice emails to donors after successful donations.

**Flow**:
```
User Makes Donation → donation_handler.php → Database Insert → 
Email System Triggered → Receipt Download Link Generated → Email with Link Sent
```

**Files Involved**:
- `donation_handler.php` - Processes donation and triggers email
- `includes/SMTPEmailHandler.php` - Sends donation invoice email
- `pages/receipt.php` - Receipt page for download/printing

**Email Content**:

- Professional thank you message
- Donation details (amount, purpose, transaction ID)
- Receipt download link with prominent call-to-action button
- Tax receipt information
- Organization contact details
- Next steps and updates information

**Features**:

- ✅ HTML-formatted professional template
- ✅ Secure receipt download link (ID + transaction validation)
- ✅ Personalized donor information
- ✅ Dual currency display (USD/BDT)
- ✅ Tax receipt compliance
- ✅ Print-optimized receipt page
- ✅ Mobile-responsive receipt design

---

### 2. Volunteer Approval System

**Purpose**: Send welcome and onboarding emails when admins approve volunteer applications.

**Flow**:

```
Admin Approves Volunteer → admin/volunteers.php → Database Update →
Email System Triggered → Welcome Email Sent
```

**Files Involved**:

- `admin/volunteers.php` - Admin interface for volunteer management
- `includes/SMTPEmailHandler.php` - Sends volunteer approval email

**Email Content**:

- Congratulations message
- Volunteer role confirmation
- Onboarding next steps
- Contact information for coordinator
- Code of conduct reminders
- Orientation session details

**Features**:

- ✅ Role-specific customization
- ✅ Professional welcome design
- ✅ Clear next steps outlined
- ✅ Contact information provided
- ✅ Branded organization template

---

### 3. Contact Form Notification System

**Purpose**: Notify administrators when visitors submit contact form inquiries.

**Flow**:

```
User Submits Contact Form → contact_handler.php → Database Insert →
Email Notification Sent to Admin
```

**Files Involved**:

- `contact_handler.php` - Processes contact form submissions
- `includes/SMTPEmailHandler.php` - Sends admin notification

**Email Content**:

- Complete contact form submission details
- Sender information (name, email, phone)
- Subject and message content
- Quick action links (reply directly)
- Timestamp of submission

**Features**:

- ✅ Complete form data forwarding
- ✅ Admin-focused notification design
- ✅ Quick reply functionality
- ✅ Professional admin interface
- ✅ Contact management integration

---

## ⚙️ Technical Implementation

### SMTP Configuration

**Email Service**: Gmail SMTP
**Security**: Gmail App Passwords (2-Factor Authentication required)
**Encryption**: TLS/STARTTLS (Port 587) or SSL (Port 465)

```php
// Configuration (from .env file)
GMAIL_USERNAME="your_email_username"
GMAIL_APP_PASSWORD="your_app_password"
FROM_EMAIL="your_email@gmail.com"
FROM_NAME="Smiling Foundation"

// SMTP settings are hardcoded in email_config.php:
SMTP_HOST = 'smtp.gmail.com'
SMTP_PORT = 587
SMTP_SECURE = 'tls'
```

### Email Handler Classes

#### SMTPEmailHandler Class

- **Purpose**: Full-featured SMTP implementation
- **Methods**:
  - `sendDonationInvoice()` - Donation receipts
  - `sendVolunteerApproval()` - Volunteer welcome emails
  - `sendContactNotification()` - Admin notifications
- **Features**: Custom SMTP client, error handling, debugging

#### EmailHandler Class (Fallback)

- **Purpose**: Simplified email sending using PHP's mail() function
- **Use Case**: Local development or when SMTP fails
- **Methods**: Same interface as SMTPEmailHandler

### Receipt Download System

**File**: `pages/receipt.php`
**Purpose**: Web-based receipt page for donation receipt viewing and printing
**Features**:
- Professional receipt layout with organization branding
- Secure access validation (donation ID + transaction ID required)
- Print-optimized CSS for professional printing
- Mobile-responsive design
- Tax receipt compliance information
- Browser-based PDF generation via print function

---

## 🛠️ Configuration & Setup

### Environment Configuration

**File**: `.env` (root directory)
```properties
# Gmail SMTP Configuration
GMAIL_USERNAME="your_email_username"
GMAIL_APP_PASSWORD="your_app_password"
FROM_EMAIL="your_email@gmail.com"
FROM_NAME="Smiling Foundation"
```

### Email Configuration File

**File**: `config/email_config.php`

- Loads environment variables from `.env`
- Defines SMTP constants
- Sets organization details
- Configures paths and directories

### Directory Structure

```
smilingfoundation/
├── config/
│   └── email_config.php          # Email configuration
├── includes/
│   ├── SMTPEmailHandler.php      # Main SMTP email handler
│   └── EmailHandler.php          # Fallback email handler
├── pages/
│   └── receipt.php               # Receipt download page
├── .env                          # Environment variables
├── donation_handler.php          # Donation processing
├── contact_handler.php           # Contact form processing
└── admin/
    └── volunteers.php            # Volunteer management
```

---
