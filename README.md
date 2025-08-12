# Mother Jane Legacy Foundation Website

A responsive, modern website for the Mother Jane Legacy Foundation, built with PHP, Bootstrap, and MySQL. The website features a fully responsive design, admin dashboard, blog system, and donation management.

## Features

### Frontend
- **Fully Responsive Design**: Mobile-first approach with Bootstrap 5
- **Modern UI/UX**: Clean, professional design with smooth animations
- **SEO Optimized**: Meta tags, structured data, and clean URLs
- **Fast Loading**: Optimized images and efficient code structure
- **Accessibility**: WCAG compliant with proper ARIA labels

### Backend
- **Admin Dashboard**: Complete content management system
- **Blog System**: Create, edit, and manage blog posts with SEO features
- **Contact Management**: Handle contact form submissions
- **Volunteer Applications**: Manage volunteer applications
- **Donation Tracking**: Track and manage donations
- **Newsletter System**: Email subscription management
- **Team Management**: Manage team member profiles
- **Project Management**: Create and manage projects

### Security
- **SQL Injection Protection**: Prepared statements
- **XSS Protection**: Input sanitization and output escaping
- **CSRF Protection**: Form token validation
- **Password Hashing**: Secure password storage with bcrypt
- **Session Management**: Secure session handling

## Technology Stack

- **Frontend**: HTML5, CSS3, JavaScript, Bootstrap 5
- **Backend**: PHP 7.4+, MySQL 5.7+
- **Server**: Apache/Nginx
- **Icons**: Font Awesome 7.0
- **Database**: MySQL with PDO

## Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache/Nginx web server
- Composer (optional, for dependency management)

### Step 1: Clone the Repository
```bash
git clone <repository-url>
cd mjl
```

### Step 2: Database Setup
1. Create a MySQL database named `mjl_foundation`
2. Import the database schema:
```bash
mysql -u your_username -p mjl_foundation < database/mjl_foundation.sql
```

### Step 3: Configuration
1. Copy the configuration file:
```bash
cp includes/config.php.example includes/config.php
```

2. Edit `includes/config.php` with your database credentials:
```php
define('DB_HOST', 'localhost');
define('DB_USER', 'your_username');
define('DB_PASS', 'your_password');
define('DB_NAME', 'mjl_foundation');
```

3. Update the site URL:
```php
define('SITE_URL', 'http://your-domain.com/mjl');
```

### Step 4: File Permissions
Set proper permissions for upload directories:
```bash
chmod 755 assets/images/
chmod 755 admin/uploads/
```

### Step 5: Admin Access
Default admin credentials:
- **Username**: admin
- **Password**: admin123

**Important**: Change the default password immediately after first login!

## File Structure

```
mjl/
├── admin/                 # Admin dashboard
│   ├── index.php         # Admin login
│   ├── dashboard.php     # Main dashboard
│   ├── blog-posts.php    # Blog management
│   ├── contact-submissions.php
│   ├── volunteers.php
│   ├── donations.php
│   ├── projects.php
│   ├── team.php
│   ├── settings.php
│   └── logout.php
├── assets/
│   ├── images/           # Website images
│   ├── styles/
│   │   └── Styles.css    # Main stylesheet
│   └── js/
│       └── main.js       # Main JavaScript
├── components/           # Old component files (deprecated)
├── database/
│   └── mjl_foundation.sql
├── features/             # Feature-specific pages
│   ├── donations/
│   └── payments/
├── includes/             # PHP includes
│   ├── config.php        # Database configuration
│   ├── header.php        # Header template
│   ├── footer.php        # Footer template
│   └── newsletter-subscribe.php
├── pages/                # Main website pages
│   ├── home.php          # Homepage
│   ├── about.php         # About page
│   ├── contact.php       # Contact page
│   ├── get-involved.php  # Get involved page
│   ├── projects.php      # Projects page
│   └── blog.php          # Blog page
└── index.php             # Entry point
```

## Usage

### Frontend
1. Navigate to your website URL
2. The homepage will display automatically
3. Use the navigation menu to explore different sections

### Admin Dashboard
1. Navigate to `/admin/`
2. Login with admin credentials
3. Use the sidebar to access different management sections

### Creating Blog Posts
1. Login to admin dashboard
2. Go to "Blog Posts" section
3. Click "New Blog Post"
4. Fill in the form with:
   - Title
   - Content (supports HTML)
   - Excerpt
   - Featured image
   - SEO meta tags
5. Set status to "Published" and save

### Managing Content
- **Contact Messages**: View and respond to contact form submissions
- **Volunteers**: Review and manage volunteer applications
- **Donations**: Track donation history and status
- **Projects**: Create and manage projects
- **Team**: Add/edit team member profiles
- **Settings**: Configure site settings

## Customization

### Styling
- Edit `assets/styles/Styles.css` for custom styles
- CSS variables are defined at the top for easy color customization
- Bootstrap classes are used for layout and components

### Content
- Update content in respective PHP files
- Images can be replaced in `assets/images/`
- Logo can be changed in `assets/images/logo.png`

### Configuration
- Site settings can be managed through the admin dashboard
- Database settings are in `includes/config.php`
- Email settings can be configured for contact forms

## SEO Features

- Clean, semantic HTML structure
- Meta title and description for each page
- Open Graph tags for social media sharing
- Structured data markup
- XML sitemap generation
- Clean URLs with proper routing

## Performance Optimization

- Optimized images with proper sizing
- Minified CSS and JavaScript
- Efficient database queries
- Caching strategies
- Lazy loading for images
- CDN integration ready

## Security Considerations

- All user inputs are sanitized
- SQL injection protection with prepared statements
- XSS protection with output escaping
- CSRF tokens for forms
- Secure password hashing
- Session security
- File upload restrictions

## Browser Support

- Chrome (latest)
- Firefox (latest)
- Safari (latest)
- Edge (latest)
- Internet Explorer 11+

## Mobile Responsiveness

- Mobile-first design approach
- Responsive navigation
- Touch-friendly interface
- Optimized for all screen sizes
- Fast loading on mobile devices

## Maintenance

### Regular Tasks
- Update PHP and MySQL versions
- Monitor error logs
- Backup database regularly
- Update dependencies
- Check for security updates

### Backup
```bash
# Database backup
mysqldump -u username -p mjl_foundation > backup.sql

# File backup
tar -czf website-backup.tar.gz /path/to/mjl/
```

## Troubleshooting

### Common Issues

1. **Database Connection Error**
   - Check database credentials in `includes/config.php`
   - Ensure MySQL service is running
   - Verify database exists

2. **Admin Login Issues**
   - Reset admin password in database
   - Check session configuration
   - Verify file permissions

3. **Image Upload Problems**
   - Check directory permissions
   - Verify upload limits in PHP configuration
   - Check file size restrictions

4. **Responsive Issues**
   - Test on different devices
   - Check Bootstrap CSS is loading
   - Verify viewport meta tag

## Support

For technical support or questions:
- Email: contact@mjlegacyfoundation.org
- Check the documentation in the `/docs` folder
- Review the code comments for implementation details

## License

This project is proprietary software developed for the Mother Jane Legacy Foundation.

## Changelog

### Version 1.0.0 (Current)
- Initial release
- Responsive design implementation
- Admin dashboard
- Blog system
- Contact management
- Volunteer application system
- Donation tracking
- SEO optimization

---

**Note**: This is a production-ready website. Make sure to:
- Change default admin credentials
- Configure proper email settings
- Set up SSL certificate
- Configure backup systems
- Test thoroughly before going live 