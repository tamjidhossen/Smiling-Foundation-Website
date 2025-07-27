# 📧 Smiling Foundation Email System - Setup Guide

## 🎯 Overview

This email system automatically sends:

- **Donation Invoices**: Professional PDF invoices sent to donors after successful donations
- **Volunteer Approvals**: Welcome emails sent when admins approve volunteer applications

## 🚀 Quick Setup (5 Minutes)

### Step 1: Gmail Configuration

1. **Enable 2-Step Verification** on your Google Account:

   - Go to [Google Account Security](https://myaccount.google.com/security)
   - Turn on 2-Step Verification if not already enabled

2. **Generate App Password**:
   - Go to **Security** → **App Passwords**
   - Select "Mail" as app, "Other (Custom name)" as device
   - Name it "Smiling Foundation Website"
   - Copy the 16-character password (e.g., `abcd efgh ijkl mnop`)

### Step 2: Configure Email Settings

1. Open your website admin panel
2. Go to **Admin Panel** → **Email Settings**
3. Fill the form:

   - **Gmail Address**: Your Gmail address (e.g., `yourname@gmail.com`)
   - **App Password**: The 16-character password from Step 1
   - **From Email**: Same as Gmail address
   - **From Name**: `Smiling Foundation`
   - **Test Email**: Your email to receive test message

4. Click **Save Configuration** - a test email will be sent!

### Step 3: Test the System

1. Visit `http://localhost/smilingfoundation/test_email.php`
2. Update test email addresses to your email
3. Click **Send Test Donation Email** - check for PDF attachment
4. Click **Send Test Volunteer Email** - check formatting

## 📂 File Structure

```
smilingfoundation/
├── config/
│   └── email_config.php          # Email configuration
├── includes/
│   ├── SMTPEmailHandler.php      # Main email handler
│   └── InvoicePDFGenerator.php   # PDF invoice generator
├── admin/
│   └── email_config.php          # Admin settings interface
├── temp/pdf/                     # Temporary PDF storage
└── test_email.php                # Testing interface
```

## 🔧 How It Works

### Donation Process

1. User makes donation → `donation_handler.php`
2. System generates invoice PDF → `InvoicePDFGenerator.php`
3. Email sent with PDF attachment → `SMTPEmailHandler.php`
4. Donor receives professional invoice email

### Volunteer Approval

1. Admin approves volunteer → `admin/volunteers.php`
2. System triggers approval email → `SMTPEmailHandler.php`
3. Volunteer receives welcome email with next steps

## 📧 Email Templates

### Donation Invoice Email

- **Subject**: "Thank you for your donation - Invoice #[ID]"
- **Content**: Professional thank you with donation details
- **Attachment**: HTML-based PDF invoice
- **Styling**: Branded with organization colors and logo

### Volunteer Approval Email

- **Subject**: "Congratulations! Your volunteer application has been approved"
- **Content**: Welcome message with onboarding steps
- **Information**: Next steps, contact details, expectations
- **Styling**: Professional welcome design

## 🛠️ Admin Features

### Email Configuration Panel

- **Location**: Admin Panel → Email Settings
- **Features**:
  - Gmail SMTP setup
  - Test email functionality
  - Configuration validation
  - Setup guide with screenshots

### Monitoring & Logs

- **Error Logging**: All email attempts logged to server error log
- **Success Tracking**: Successful sends recorded
- **Debug Mode**: Available for troubleshooting

## 🔒 Security Features

- **App Password Authentication**: Uses Gmail App Passwords (more secure than regular passwords)
- **Input Validation**: All email addresses and data validated before sending
- **Secure Storage**: Email credentials stored in protected configuration file
- **Temporary File Cleanup**: PDF attachments automatically deleted after sending
- **Error Handling**: Graceful failure handling without exposing sensitive data

## 🧪 Testing Checklist

### Pre-Testing Requirements

- [ ] Gmail 2-Step Verification enabled
- [ ] App Password generated and copied
- [ ] Admin email settings configured
- [ ] Test email addresses updated

### Email Testing

- [ ] Visit `test_email.php` in browser
- [ ] Test donation email sends successfully
- [ ] PDF invoice attachment downloads and opens correctly
- [ ] Test volunteer approval email sends successfully
- [ ] Email templates display properly in different email clients
- [ ] Error handling works (try with wrong credentials)

### Integration Testing

- [ ] Make test donation through website - email should auto-send
- [ ] Approve volunteer in admin panel - email should auto-send
- [ ] Check server error logs for any issues
- [ ] Verify all email content is personalized correctly

## 🚨 Troubleshooting

### Common Issues & Solutions

#### "Authentication failed" Error

- **Solution**: Verify App Password is correct (16 characters, no regular password)
- **Check**: 2-Step Verification is enabled on Google Account

#### "Connection failed" Error

- **Solution**: Check server firewall allows outbound SMTP (port 587/465)
- **Alternative**: Try different SMTP port (587 vs 465)

#### "Email not sending" but no errors

- **Solution**: Check server's ability to make external connections
- **Test**: Try sending to different email providers (Gmail, Yahoo, etc.)

#### PDF attachment not working

- **Solution**: Check `temp/pdf/` directory permissions (755)
- **Verify**: Temporary file is created and accessible

#### Template not displaying correctly

- **Solution**: Check HTML email support in email client
- **Test**: View in different email clients (Gmail, Outlook, etc.)

### Debug Steps

1. **Check Configuration**: Visit Admin → Email Settings
2. **Test Interface**: Use `test_email.php` to isolate issues
3. **Error Logs**: Check server error logs for detailed messages
4. **SMTP Test**: Use external SMTP testing tools
5. **Firewall**: Verify server can make outbound connections

## 📊 Success Indicators

### System Working Correctly

- ✅ Test emails send within 10 seconds
- ✅ PDF attachments open and display correctly
- ✅ Donation emails trigger automatically after successful donation
- ✅ Volunteer approval emails trigger when admin approves
- ✅ Email templates look professional and branded
- ✅ No errors in server logs related to email sending

### Performance Metrics

- **Email Delivery Time**: < 10 seconds for test emails
- **Success Rate**: > 95% for email delivery
- **PDF Generation**: < 2 seconds per invoice
- **Error Rate**: < 5% acceptable failure rate

## 🔄 Maintenance

### Regular Tasks

- **Monthly**: Check email delivery rates
- **Quarterly**: Update Gmail App Password if needed
- **As Needed**: Monitor server logs for errors
- **Updates**: Keep email templates current with organization changes

### Backup & Recovery

- **Configuration**: Backup `config/email_config.php`
- **Templates**: Email templates are in code - version controlled
- **Logs**: Monitor server logs for email sending history

## 📈 Future Enhancements

### Immediate Improvements

1. **Email Analytics**: Track open rates and click-through rates
2. **Queue System**: Handle high-volume email sending
3. **Template Editor**: Admin interface for customizing email templates

### Advanced Features

1. **Multi-language**: Email templates in multiple languages
2. **Scheduling**: Delayed or scheduled email sending
3. **Integration**: Connect with email marketing services
4. **Automation**: More sophisticated email workflows

## 📞 Support

### Getting Help

1. **Documentation**: This guide covers most scenarios
2. **Testing**: Use `test_email.php` for troubleshooting
3. **Logs**: Check server error logs for detailed error messages
4. **Community**: Search for PHPMailer/SMTP related solutions online

### Contact Information

- **Admin Panel**: Use Email Settings page for configuration
- **Test Interface**: `test_email.php` for debugging
- **Error Logs**: Check server logs for detailed error information

---

## ✅ Installation Complete!

Your email system is now ready for production use. The system will automatically:

- Send invoice emails when donations are made
- Send welcome emails when volunteers are approved
- Provide professional, branded email templates
- Handle errors gracefully with logging

**Next Steps**: Test the system with real donations and volunteer approvals to ensure everything works perfectly in your environment.

🎉 **Congratulations! Your automated email system is now live!**
