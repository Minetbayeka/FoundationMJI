-- MJL Foundation Database Schema
-- Create database
CREATE DATABASE IF NOT EXISTS mjl_foundation;
USE mjl_foundation;

-- Users table for admin authentication
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'editor') DEFAULT 'editor',
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Blog posts table
CREATE TABLE blog_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    excerpt TEXT,
    content LONGTEXT NOT NULL,
    featured_image VARCHAR(255),
    author_id INT,
    status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
    meta_title VARCHAR(255),
    meta_description TEXT,
    meta_keywords VARCHAR(255),
    view_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    published_at TIMESTAMP NULL,
    FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE SET NULL
);

-- Contact form submissions
CREATE TABLE contact_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    ip_address VARCHAR(45),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Volunteer applications
CREATE TABLE volunteer_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    skills TEXT NOT NULL,
    message TEXT,
    status ENUM('pending', 'reviewed', 'accepted', 'rejected') DEFAULT 'pending',
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Newsletter subscriptions
CREATE TABLE newsletter_subscriptions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    is_active BOOLEAN DEFAULT TRUE,
    subscribed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    unsubscribed_at TIMESTAMP NULL
);

-- Donations table
CREATE TABLE donations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    donor_name VARCHAR(100) NOT NULL,
    donor_email VARCHAR(100) NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    currency VARCHAR(3) DEFAULT 'USD',
    payment_method ENUM('online', 'bank_transfer', 'cash', 'check') NOT NULL,
    payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
    transaction_id VARCHAR(255),
    purpose VARCHAR(255),
    is_anonymous BOOLEAN DEFAULT FALSE,
    message TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Projects table
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    description TEXT,
    content LONGTEXT,
    featured_image VARCHAR(255),
    category ENUM('health', 'education', 'agriculture', 'psychosocial', 'economic') NOT NULL,
    status ENUM('active', 'completed', 'planned') DEFAULT 'active',
    start_date DATE,
    end_date DATE,
    target_amount DECIMAL(10,2),
    raised_amount DECIMAL(10,2) DEFAULT 0.00,
    location VARCHAR(255),
    beneficiaries_count INT,
    is_featured BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Team members table
CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    position VARCHAR(100) NOT NULL,
    bio TEXT,
    image VARCHAR(255),
    email VARCHAR(100),
    phone VARCHAR(20),
    social_facebook VARCHAR(255),
    social_twitter VARCHAR(255),
    social_linkedin VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Settings table for site configuration
CREATE TABLE settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value TEXT,
    setting_type ENUM('text', 'textarea', 'number', 'boolean', 'json') DEFAULT 'text',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO users (username, email, password, full_name, role) VALUES 
('admin', 'admin@mjlegacyfoundation.org', '$2y$10$irMobnreJ.gaPVLWcQTm1eecv9fnZkQouO5BhTo7tpESIvNcAaWGC', 'MJL Foundation Admin', 'admin');

-- Insert default settings
INSERT INTO settings (setting_key, setting_value, setting_type, description) VALUES
('site_name', 'Mother Jane Legacy Foundation', 'text', 'Website name'),
('site_description', 'A world where the rights and dignity of underprivileged children are met', 'textarea', 'Website description'),
('contact_email', 'contact@mjlegacyfoundation.org', 'text', 'Primary contact email'),
('contact_phone', '+237 6 79267828', 'text', 'Primary contact phone'),
('contact_address', 'Mankon - Bamenda, Cameroon', 'text', 'Organization address'),
('facebook_url', '#', 'text', 'Facebook page URL'),
('twitter_url', '#', 'text', 'Twitter page URL'),
('instagram_url', '#', 'text', 'Instagram page URL'),
('donation_goal', '100000', 'number', 'Annual donation goal'),
('volunteers_count', '50', 'number', 'Number of active volunteers'),
('children_helped', '500', 'number', 'Number of children helped'),
('communities_served', '25', 'number', 'Number of communities served');

-- Insert sample team members
INSERT INTO team_members (name, position, bio, image, email, sort_order) VALUES
('Dr. Akwa Gilbert Mua', 'Executive Director', 'Dr Akwa Gilbert is a medical doctor and passionate for the poor and the needy. His story is a beautiful one because he started from below and has accomplished many things.', 'Akwa.jpg', 'gilbert@mjlegacyfoundation.org', 1),
('Dr. Ateh Cavour', 'Field Agent', 'Dedicated field agent working directly with communities to provide healthcare and support services.', 'Ateh.jpg', 'cavour@mjlegacyfoundation.org', 2),
('Fon Blaise Fru', 'Media Manager', 'Responsible for managing our communication and media presence to raise awareness about our mission.', 'fon.jpg', 'blaise@mjlegacyfoundation.org', 3),
('Aben Cistus Tita', 'Field Agent', 'Committed field agent working to bring healthcare and education to rural communities.', 'tita.jpg', 'cistus@mjlegacyfoundation.org', 4);

-- Insert sample projects
INSERT INTO projects (title, slug, description, category, status, target_amount, location, beneficiaries_count, is_featured) VALUES
('Health Problem', 'health-problem', 'Coupled with the ongoing Anglophone crisis, many rural areas / conflict zones have no health facilities. We provide essential healthcare services to vulnerable communities.', 'health', 'active', 75000.00, 'Bamenda Rural Areas', 300, TRUE),
('Well Being', 'well-being', 'The impact of the crisis has caused many to lose their jobs and everyone has turned to farming as the last resort. We support sustainable agricultural practices and economic empowerment.', 'agriculture', 'active', 50000.00, 'North West Region', 200, TRUE),
('Education', 'education', 'Going to school becomes impossible to some people. We provide educational support, school materials, and tuition assistance to vulnerable children.', 'education', 'active', 60000.00, 'Bamenda Region', 250, TRUE),
('Rural Health Outreach', 'rural-health-outreach', 'Providing healthcare services to rural communities affected by the Anglophone crisis', 'health', 'active', 50000.00, 'Bamenda Rural Areas', 200, FALSE),
('Education Support Program', 'education-support-program', 'Supporting children with school materials and tuition fees', 'education', 'active', 30000.00, 'Bamenda Region', 150, FALSE),
('Agricultural Training Initiative', 'agricultural-training-initiative', 'Teaching sustainable farming practices to vulnerable children and families', 'agriculture', 'planned', 25000.00, 'North West Region', 100, FALSE);

-- Create indexes for better performance
CREATE INDEX idx_blog_posts_status ON blog_posts(status);
CREATE INDEX idx_blog_posts_slug ON blog_posts(slug);
CREATE INDEX idx_contact_submissions_created ON contact_submissions(created_at);
CREATE INDEX idx_volunteer_applications_status ON volunteer_applications(status);
CREATE INDEX idx_donations_status ON donations(payment_status);
CREATE INDEX idx_projects_status ON projects(status);
CREATE INDEX idx_projects_category ON projects(category); 