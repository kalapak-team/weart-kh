# WeArt - Khmer Traditional Art Showcase 🎨

![WeArt Banner](https://img.shields.io/badge/WeArt-Khmer%20Art%20Showcase-gold?style=for-the-badge&logo=palette&colorB=8B0000)
![PHP](https://img.shields.io/badge/PHP-7.4%2B-777BB4?logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?logo=mysql&logoColor=white)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?logo=bootstrap&logoColor=white)

A beautiful, responsive PHP website showcasing the rich heritage of Khmer traditional arts with a complete admin panel for content management.

![WeArt Preview](https://via.placeholder.com/800x400/8B0000/FFFFFF?text=WeArt+Khmer+Traditional+Art+Showcase)

## ✨ Features

### 🎨 Frontend Website
- **Modern Khmer-inspired Design**: Golden temple patterns, Angkor Wat silhouettes, and Apsara flourishes
- **Responsive Layout**: Mobile-friendly design that works on all devices
- **Art Gallery**: Lightbox-enabled artwork showcase
- **Artist Profiles**: Dedicated pages for Cambodian artists
- **Contact Form**: PHP-backed contact form with validation

### ⚙️ Admin Panel
- **Secure Authentication**: Admin login system with password hashing
- **Dashboard**: Overview statistics and quick actions
- **Content Management**: CRUD operations for artworks and artists
- **Media Upload**: Image upload system for gallery and profiles
- **Page Management**: Edit about and contact content

## 🗂️ Project Structure

```
weart/
├── index.php                 # Homepage
├── about.php                 # About page
├── gallery.php               # Gallery page
├── artists.php               # Artists page
├── contact.php               # Contact page
├── admin/                    # Admin panel
│   ├── index.php            # Admin login
│   ├── dashboard.php        # Admin dashboard
│   ├── gallery.php          # Manage artworks
│   ├── artists.php          # Manage artists
│   ├── pages.php            # Manage static pages
│   └── logout.php           # Admin logout
├── assets/                   # Static assets
│   ├── css/
│   │   └── style.css        # Custom styles
│   ├── js/
│   │   └── script.js        # Custom JavaScript
│   └── images/              # Theme images
├── includes/                 # Reusable components
│   ├── header.php           # Site header
│   └── footer.php           # Site footer
├── config/
│   └── db.php               # Database configuration
├── uploads/                  # User-uploaded images
└── README.md                 # Project documentation
```

## 🚀 Installation Guide

### Prerequisites
- XAMPP (Apache + MySQL + PHP)
- Web browser
- Git (optional)

### Step-by-Step Setup

1. **Install XAMPP**
   ```bash
   Download from https://www.apachefriends.org/
   Install and start Apache & MySQL services
   ```

2. **Clone or Download Project**
   ```bash
   # Option 1: Clone repository
   git clone https://github.com/yourusername/weart.git
   
   # Option 2: Download ZIP and extract to htdocs folder
   ```

3. **Place Project in XAMPP**
   ```
   Move the 'weart' folder to: C:\xampp\htdocs\
   ```

4. **Start Services**
   - Open XAMPP Control Panel
   - Start Apache and MySQL

5. **Access Website**
   ```
   Open browser and navigate to: http://localhost/weart/
   ```

6. **Access Admin Panel**
   ```
   Navigate to: http://localhost/weart/admin/
   Use credentials: admin / admin123
   ```

### Database Setup

The application will automatically create the database and tables on first visit. Manual setup is optional:

1. **phpMyAdmin Method**
   ```sql
   CREATE DATABASE weart_db;
   USE weart_db;
   -- Import the SQL file from config/weart_db.sql if provided
   ```

2. **Automatic Method**
   - Just visit the website
   - Database and tables will be created automatically

## 🎨 Design Elements

### Color Scheme
- **Gold**: #D4AF37 (Primary accent)
- **Deep Red**: #8B0000 (Primary color)
- **Dark Green**: #2E8B57 (Secondary color)
- **Black**: #1A1A1A (Text)
- **Ivory**: #FFFFF0 (Background)

### Typography
- **Headings**: Playfair Display (Elegant, traditional feel)
- **Body Text**: Source Sans Pro (Clean, readable)

### Design Features
- Khmer pattern backgrounds
- Rounded edges and elegant UI elements
- Responsive grid system
- Hover effects and smooth transitions

## 👨‍💻 Admin Usage

### Managing Content

1. **Artworks Management**
   - Add new artworks with images
   - Edit existing artwork details
   - Assign artworks to artists
   - Delete artworks

2. **Artists Management**
   - Create artist profiles
   - Upload artist photos
   - Write detailed biographies
   - Manage artist portfolio

3. **Page Content**
   - Edit About page content
   - Update contact information
   - Modify page text without coding

### Security Features
- Password hashing with bcrypt
- SQL injection prevention using PDO prepared statements
- Session management
- Input validation and sanitization

## 🔧 Customization

### Adding New Pages
1. Create new PHP file in root directory
2. Include header.php and footer.php
3. Add page to navigation in header.php

### Modifying Styles
- Edit `assets/css/style.css`
- Color variables are defined at the top of the file
- Bootstrap classes are used throughout for consistency

### Database Modifications
- Edit `config/db.php` for database settings
- Table structures can be modified in the createTables() function

## 🌐 Browser Support

| Browser | Version | Support |
|---------|---------|---------|
| Chrome | 60+ | ✅ Full |
| Firefox | 60+ | ✅ Full |
| Safari | 12+ | ✅ Full |
| Edge | 79+ | ✅ Full |
| Opera | 50+ | ✅ Full |

## 📱 Responsive Breakpoints

- **Mobile**: < 768px
- **Tablet**: 768px - 992px
- **Desktop**: > 992px

## 🐛 Troubleshooting

### Common Issues

1. **"Failed to open stream" error**
   - Check that all files are in correct locations
   - Verify folder permissions

2. **Database connection errors**
   - Ensure MySQL is running in XAMPP
   - Check database credentials in config/db.php

3. **Image upload issues**
   - Verify uploads directory has write permissions
   - Check file size limits

4. **Session errors**
   - Clear browser cookies and cache
   - Restart Apache service

### Debug Mode

To enable error reporting for debugging, add this to config/db.php:
```php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
```

## 📄 License

This project is created for educational and portfolio purposes. Please attribute if reused.

## 🤝 Contributing

We welcome contributions to improve WeArt:

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Submit a pull request

## 🏆 Acknowledgments

- Inspired by Khmer traditional art and culture
- Built with Bootstrap 5 for responsive design
- PHP backend with MySQL database
- Lightbox2 for image viewing

## 📞 Support

For support or questions:
- Create an issue on GitHub
- Email: info@weart.com

## 🔗 Live Demo

[View Live Demo](http://localhost/weart/) (When running on localhost)

---

<div align="center">
  
Made with ❤️ for Khmer cultural preservation

[![GitHub stars](https://img.shields.io/github/stars/yourusername/weart?style=social)](https://github.com/yourusername/weart)
[![GitHub forks](https://img.shields.io/github/forks/yourusername/weart?style=social)](https://github.com/yourusername/weart)

</div>