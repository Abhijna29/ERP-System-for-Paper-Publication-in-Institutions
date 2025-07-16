ERP System for Paper Publication in Institutions

A Laravel-based Enterprise Resource Planning (ERP) system designed to streamline and manage the end-to-end process of research paper and book chapter publication within academic institutions. The platform supports roles such as researchers, reviewers, admins, institutions, and departments, with dedicated workflows and permissions for each.

## Features

### Research & Publications
- Submit research papers and book chapters with metadata and file uploads
- Assign DOIs, journal details, and co-authorship
- Link publications to journals (Scopus, PubMed, etc.)
- Track paper status: Draft → Submitted → Reviewed → Published

### User Roles & Access
- **Admin**: Full control over journals, papers, users, and system settings
- **Institution**: Manages departments, researchers, and subscriptions
- **Researcher**: Submits papers, views status, uploads patents, copyrights
- **Reviewer**: Reviews assigned papers with deadlines and feedback

### IP Management
- Patent filing and certificate uploads
- Copyright tracking
- Trademarks and design rights management

### Subscription & Payments
- Institution-based subscription system with plan management
- Razorpay integration for secure payments
- Controls submission & download limits

### Dashboard & Analytics
- Role-specific dashboards with activity tracking
- Charts showing submissions, reviews, deadlines, and statuses

---

## 🛠️ Tech Stack

- **Backend**: Laravel 10+
- **Frontend**: Blade, Bootstrap 5, CSS
- **Database**: MySQL
- **Authentication**: Laravel UI
- **Payments**: Razorpay
- **AI Integration**: OpenAI API for review summarization

