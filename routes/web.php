<?php

use App\Http\Controllers\Institution\InstitutionDashboardController;
use App\Http\Controllers\Institution\InstitutionDepartmentController;
use App\Http\Controllers\Institution\InstitutionReviewController;
use App\Http\Controllers\Institution\InstitutionUserController;
use App\Http\Controllers\Institution\InstitutionSubmissionController;

use App\Http\Controllers\SubCategoryController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\admin\AdminDashboardController;
use App\Http\Controllers\admin\AdminInvoiceController;
use App\Http\Controllers\admin\AdminPaperController;
use App\Http\Controllers\admin\AdminPaymentReportController;
use App\Http\Controllers\admin\ApproveController;
use App\Http\Controllers\Admin\BookChapterAdminController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\admin\InstituteController;
use App\Http\Controllers\admin\JournalController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChildCategoryController;
use App\Http\Controllers\Department\DepartmentDashboardController;
use App\Http\Controllers\Department\DepartmentReviewController;
use App\Http\Controllers\Department\DepartmentSubmissionController;
use App\Http\Controllers\Department\DepartmentUserController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PasswordChangeController;
use App\Http\Controllers\Researcher\BookChapterController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Researcher\ResearcherDashboardController;
use App\Http\Controllers\Researcher\ResearcherController;
use App\Http\Controllers\Researcher\ResearchPaperController;
use App\Http\Controllers\Reviewer\ReviewerController;
use App\Http\Controllers\Reviewer\ReviewerDashboardController;
use App\Http\Controllers\SearchPaperController;
use App\Http\Controllers\UserSubscriptionController;
use App\Http\Controllers\FaqPublicController;
use App\Http\Controllers\Institution\InstitutionSubscriptionController;

Route::get('/', function () {
    return view('auth/login');
});

Auth::routes();

//notifications
Route::middleware(['auth', 'force.password.change'])->group(function () {
    Route::get('/notifications', [NotificationController::class, 'getNotifications'])->name('notifications');
    Route::post('/notifications/mark-as-read', [NotificationController::class, 'markAsRead'])->name('notifications.markAsRead');
    Route::post('/notification/mark-as-one-read/{id}', [NotificationController::class, 'markSingleAsRead'])->name('notifications.markOne');
});

Route::middleware(['auth', 'force.password.change', 'role:researcher'])->prefix('researcher')->group(function () {
    Route::get('/', [ResearcherDashboardController::class, 'index'])->name('researcher.dashboard');
    Route::get('/create', [ResearchPaperController::class, 'create'])->name('papers.create');
    Route::post('/papers', [ResearchPaperController::class, 'store'])->name('papers.store');
    Route::get('/papers_submitted', [ResearchPaperController::class, 'submitted'])->name('papers.submitted');
    Route::post('/papers/{id}/resubmit', [ResearchPaperController::class, 'resubmit'])->name('papers.resubmit');

    Route::get('/submit-book-chapter', [BookChapterController::class, 'create'])->name('book-chapters.create');
    Route::post('/submit-book-chapter', [BookChapterController::class, 'store'])->name('researcher.book-chapters.store');
    Route::get('/books/by-genre/{genre}', [BookChapterController::class, 'booksByGenre'])->name('books.by-genre');
    Route::get('/book-chapters/submitted', [BookChapterController::class, 'chapterSubmitted'])->name('chapters.submitted');
    Route::post('/book-chapters/{id}/resubmit', [BookChapterController::class, 'resubmit'])->name('chapters.resubmit');

    Route::get('/subcategories/{categoryId}', [ResearchPaperController::class, 'getSubCategories'])->name('subcategories.get');
    Route::get('/childcategories/{subCategoryId}', [ResearchPaperController::class, 'getChildCategories'])->name('childcategories.get');

    Route::get('/invoices', [ResearcherController::class, 'myInvoices'])->name('researcher.invoices');
    Route::get('/invoice/{id}', [ResearcherController::class, 'viewInvoice'])->name('researcher.invoice.view');
    Route::get('/invoice/{id}/pay', [ResearcherController::class, 'payInvoice'])->name('researcher.invoice.pay');
    Route::post('/invoice/{id}/pay', [ResearcherController::class, 'submitInvoicePayment'])->name('researcher.invoice.pay.submit');
    Route::post('/researcher/invoice/pay/success/{id}', [ResearcherController::class, 'razorpaySuccess'])->name('researcher.invoice.razorpay.success');
});

Route::middleware(['auth', 'force.password.change', 'role:reviewer'])->prefix('reviewer')->group(function () {
    Route::get('/', [ReviewerDashboardController::class, 'index'])->name('reviewer.dashboard');
    Route::get('/reviews/{type}', [ReviewerController::class, 'index'])
        ->name('reviewer.reviews')
        ->where('type', 'paper|chapter');

    // Show Review Form (for a specific paper or chapter)
    Route::get('/review-form/{type}/{id}', [ReviewerController::class, 'showReviewForm'])
        ->name('reviewer.reviewForm')
        ->where('type', 'paper|chapter');

    // Submit Review (Papers or Chapters)
    Route::post('/submit-review/{type}/{id}', [ReviewerController::class, 'submitReview'])
        ->name('reviewer.review.submit')
        ->where('type', 'paper|chapter');
});
Route::get('/admin/institution/list/', [InstituteController::class, 'getInstitutions'])->name('institution.list');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/dashboard/admin', [AdminDashboardController::class, 'index'])->name('admin.dashboard');

    Route::get('/dashboard/admin/createInstitution', [InstituteController::class, 'index'])->name('createInstitution');
    Route::post('/admin/store', [InstituteController::class, 'store'])->name('institution.store');
    Route::put('/admin/update/{id}', [InstituteController::class, 'update'])->name('institution.update');
    Route::delete('/admin/delete/{id}', [InstituteController::class, 'destroy'])->name('institution.destroy');

    Route::post('/admin/approve/{type}/{id}', [ApproveController::class, 'approveSubmission'])->name('admin.approve.submission');
    Route::post('/admin/confirm-payment/{type}/{invoiceId}', [ApproveController::class, 'confirmPayment'])->name('admin.confirm.payment');

    Route::get('/admin/invoices', [AdminInvoiceController::class, 'listInvoices'])->name('admin.invoices');
    Route::get('/admin/invoice/{id}', [AdminInvoiceController::class, 'viewInvoice'])->name('admin.invoice.view');
    Route::patch('/admin/invoice/{id}/mark-paid', [AdminInvoiceController::class, 'markAsPaid'])->name('admin.invoice.markPaid');
    // Route::post('/invoice/store', [AdminInvoiceController::class, 'store'])->name('invoice.store');

    Route::get('/admin/payment-report', [AdminPaymentReportController::class, 'paymentReport'])->name('admin.paymentReport');

    //Suport ticket
    Route::get('/admin/support-tickets', [SupportTicketController::class, 'index'])->name('admin.supportTickets');
    Route::get('/admin/support-tickets/{ticketId}/reply', [SupportTicketController::class, 'showReplyForm'])->name('admin.supportTicket.reply');
    Route::post('/admin/support-tickets/{ticketId}/reply', [SupportTicketController::class, 'update'])->name('admin.supportTicket.update');

    Route::get('/admin/faq', [FaqController::class, 'faq'])->name('admin.faq');
    Route::get('/admin/faqs/fetch', [FaqController::class, 'fetch'])->name('admin.faq.fetch');
    Route::post('/admin/faqs/store', [FaqController::class, 'store'])->name('admin.faq.store');
    Route::put('/admin/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faq.update');
    Route::delete('/admin/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faq.destroy');

    Route::get('/dashboard/admin/list_of_paper_published', [AdminController::class, 'listOfPaper'])->name('published.papers');
    Route::get('/dashboard/admin/generate_report', [AdminController::class, 'generateReport'])->name('generateReport');

    Route::get('/dashboard/admin/scopus/select', [JournalController::class, 'scopusSelect'])->name('scopus.select');
    Route::get('/dashboard/admin/scopus/{paperId}', [JournalController::class, 'scopus'])->name('scopus');

    Route::get('/dashboard/admin/web_of_science/select', [JournalController::class, 'webOfScienceSelect'])->name('web_of_science.select');
    Route::get('/dashboard/admin/web_of_science/{paperId}', [JournalController::class, 'webOfScience'])->name('webOfScience');

    Route::get('/dashboard/admin/pub_med/select', [JournalController::class, 'pubMedSelect'])->name('pubmed.select');
    Route::get('/dashboard/admin/pub_med/{paperId}', [JournalController::class, 'pubMed'])->name('pubMed');

    Route::get('/dashboard/admin/abdc/select', [JournalController::class, 'abdcSelect'])->name('abdc.select');
    Route::get('/dashboard/admin/abdc/{paperId}', [JournalController::class, 'abdc'])->name('abdc');

    Route::get('/dashboard/admin/other/select', [JournalController::class, 'otherSelect'])->name('other.select');
    Route::get('/dashboard/admin/other/{paperId}', [JournalController::class, 'other'])->name('other');

    Route::post('/journal/submit', [JournalController::class, 'store'])->name('journal.submit');

    Route::get('/book-chapters', [BookChapterAdminController::class, 'index'])->name('admin.bookChapters.index');
    Route::post('/admin/update/{id}', [BookChapterAdminController::class, 'update'])->name('admin.book.update');
    Route::post('/admin/delete/{id}', [BookChapterAdminController::class, 'destroy'])->name('admin.book.destroy');
    Route::get('/admin/list/', [BookChapterAdminController::class, 'getBooks'])->name('book.list');

    Route::get('/books/create', [BookChapterAdminController::class, 'createBook'])->name('admin.books.create');
    Route::post('/books/store', [BookChapterAdminController::class, 'storeBook'])->name('admin.books.store');
    Route::get('/books/chapter-metadata/', [BookChapterAdminController::class, 'bookChapterMetadata'])->name('books.chapter-metadata');
    Route::post('/admin/book-chapters/metadata', [BookChapterAdminController::class, 'storeBookChapter'])
        ->name('bookChapter.submit');
    Route::get('/dashboard/admin/book-chapters', [BookChapterAdminController::class, 'publishedChapter'])->name('admin.bookChapters.published');

    // View a single chapter
    Route::get('/dashboard/admin/book-chapters/{id}', [BookChapterAdminController::class, 'showchapter'])->name('admin.chapter.view');

    // Assign reviewers form
    Route::get('/dashboard/admin/book-chapters/{id}/assign', [BookChapterAdminController::class, 'showAssignForm'])->name('admin.chapter.assign.form');

    // Assign reviewers submit (POST)
    Route::post('/dashboard/admin/book-chapters/{id}/assign', [BookChapterAdminController::class, 'assignReviewers'])->name('admin.chapter.assign.submit');

    // Resolve flag
    Route::post('/dashboard/admin/review/flag/{id}/resolve', [BookChapterAdminController::class, 'resolveFlag'])->name('admin.chapter.flag.resolve');

    // // Approve chapter (if you have this logic)
    // Route::post('/dashboard/admin/book-chapters/{id}/approve', [BookChapterAdminController::class, 'approve'])->name('admin.chapter.approve');

    Route::get('/dashboard/admin/patent_filed', [AdminController::class, 'patentFiled'])->name('patentFiled');
    Route::get('/dashboard/admin/patent_published', [AdminController::class, 'patentPublished'])->name('patentPublished');
    Route::get('/dashboard/admin/patent_granted', [AdminController::class, 'patentGranted'])->name('patentGranted');
    Route::get('/dashboard/admin/copyright_filed', [AdminController::class, 'copyrightFiled'])->name('copyrightFiled');
    Route::get('/dashboard/admin/copyright_published', [AdminController::class, 'copyrightPublished'])->name('copyrightPublished');
    Route::get('/dashboard/admin/copyright_granted', [AdminController::class, 'copyrightGranted'])->name('copyrightGranted');
    Route::get('/dashboard/admin/trade_mark_filed', [AdminController::class, 'tradeMarkFiled'])->name('tradeMarkFiled');
    Route::get('/dashboard/admin/trade_mark_published', [AdminController::class, 'tradeMarkPublished'])->name('tradeMarkPublished');
    Route::get('/dashboard/admin/trade_mark_granted', [AdminController::class, 'tradeMarkGranted'])->name('tradeMarkGranted');
    Route::get('/dashboard/admin/design_filed', [AdminController::class, 'designFiled'])->name('designFiled');
    Route::get('/dashboard/admin/design_published', [AdminController::class, 'designPublished'])->name('designPublished');
    Route::get('/dashboard/admin/design_granted', [AdminController::class, 'designGranted'])->name('designGranted');

    Route::get('/papers', [AdminController::class, 'index'])->name('admin.papers');
    Route::get('/papers/{id}/assignReviewers', [AdminController::class, 'showAssignForm'])->name('admin.assign.form');
    Route::post('/papers/{id}/assignReviewers', [AdminController::class, 'assignReviewers'])->name('admin.assign.submit');
    Route::post('/admin/review/flag/{id}/resolve', [AdminController::class, 'resolveFlag'])->name('admin.flag.resolve');
    Route::get('/admin/paper/{id}/view', [AdminController::class, 'showPaper'])->name('admin.paper.view');

    //Subscription plans
    Route::get('/admin/create-subscription', [SubscriptionPlanController::class, 'index'])->name('admin.subscription.index');
    Route::post('/admin/create-subscription', [SubscriptionPlanController::class, 'store'])->name('admin.subscription.store');
    Route::put('/subscription/{id}', [SubscriptionPlanController::class, 'update'])->name('admin.subscription.update');
    Route::delete('/subscription/{id}', [SubscriptionPlanController::class, 'destroy'])->name('admin.subscription.delete');
});

Route::middleware(['auth', 'force.password.change', 'role:institution'])->prefix('institution')->group(function () {
    Route::get('/', [InstitutionDashboardController::class, 'index'])->name('institute.dashboard');

    // Department Management
    Route::get('/departments', [InstitutionDepartmentController::class, 'index'])->name('institution.departments');
    Route::get('departments/list', [InstitutionDepartmentController::class, 'getDepartments'])->name('departments.list');
    Route::post('/departments', [InstitutionDepartmentController::class, 'store'])->name('institution.departments.store');
    Route::post('departments/update/{id}', [InstitutionDepartmentController::class, 'update'])->name('department.update');
    Route::post('departments/delete/{id}', [InstitutionDepartmentController::class, 'destroy'])->name('department.destroy');

    // User Management
    Route::get('/users', [InstitutionUserController::class, 'index'])->name('institution.users');
    Route::get('users/list', [InstitutionUserController::class, 'getUsers'])->name('institution.users.list');
    Route::post('/users', [InstitutionUserController::class, 'store'])->name('institution.users.store');
    Route::post('users/update/{id}', [InstitutionUserController::class, 'update'])->name('institution.user.update');
    Route::post('users/delete/{id}', [InstitutionUserController::class, 'destroy'])->name('institution.user.destroy');

    // Submissions
    Route::get('/institution/submissions/{type?}', [InstitutionSubmissionController::class, 'index'])->name('institution.submissions.index');
    Route::get('/institution/submissions/{type}/{id}', [InstitutionSubmissionController::class, 'show'])->name('institution.submissions.show');
    Route::post('/institution/submissions/{type}/{id}/status', [InstitutionSubmissionController::class, 'updateStatus'])->name('institution.submissions.updateStatus');

    // Review Progress
    Route::get('/reviews', [InstitutionReviewController::class, 'index'])->name('institution.reviews');

    //Subscriptions
    Route::get('/subscription/plans', [InstitutionSubscriptionController::class, 'showPlans'])->name('institution.plans');
    Route::post('/subscription/subscribe', [InstitutionSubscriptionController::class, 'subscribe'])->name('institution.subscribe');
    Route::post('/subscription/payment-success', [InstitutionSubscriptionController::class, 'paymentSuccess'])->name('subscription.payment.success');
    Route::get('/subscription/my-subscriptions', [InstitutionSubscriptionController::class, 'mySubscriptions'])->name('subscription.mine');
    Route::get('/subscribe-required', [InstitutionSubscriptionController::class, 'redirectToSubscription'])->name('papers.redirectToSubscription');
    Route::get('/{type}/{id}/download', [InstitutionSubscriptionController::class, 'download'])
        ->where('type', 'papers|chapters')
        ->name('submission.download');
});

Route::middleware(['auth', 'force.password.change', 'role:department'])->prefix('department')->group(function () {
    Route::get('/', [DepartmentDashboardController::class, 'index'])->name('department.dashboard');

    Route::get('/users', [DepartmentUserController::class, 'index'])->name('department.users');
    Route::get('users/list', [DepartmentUserController::class, 'getUsers'])->name('department.users.list');
    Route::post('/users', [DepartmentUserController::class, 'store'])->name('department.users.store');
    Route::post('users/update/{id}', [DepartmentUserController::class, 'update'])->name('department.user.update');
    Route::post('users/delete/{id}', [DepartmentUserController::class, 'destroy'])->name('department.user.destroy');

    // Research Paper Submissions
    Route::get('/submissions/{type?}', [DepartmentSubmissionController::class, 'index'])->name('department.submissions.index');
    Route::get('/submissions/{type}/{id}', [DepartmentSubmissionController::class, 'show'])->name('department.submissions.show');
    Route::post('/submissions/{type}/{id}/status', [DepartmentSubmissionController::class, 'updateStatus'])->name('department.submissions.updateStatus');

    // Review Progress
    Route::get('/reviews', [DepartmentReviewController::class, 'index'])->name('department.reviews');
});

Route::prefix('dashboard/admin/category')->name('admin.category.')->group(function () {
    Route::get('/category', [CategoryController::class, 'index'])->name('index');
    Route::post('/category', [CategoryController::class, 'store'])->name('store');
    Route::get('/category/{id}/edit', [CategoryController::class, 'edit'])->name('edit');
    Route::put('/category/{id}', [CategoryController::class, 'update'])->name('update');
    Route::delete('/category/{id}', [CategoryController::class, 'destroy'])->name('destroy');
    Route::get('subcategories/{id}', [CategoryController::class, 'getSubcategories']);
});

Route::prefix('dashboard/admin')->name('admin.')->group(function () {
    // Route::resource('Category', CategoryController::class);
    Route::resource('subCategory', SubCategoryController::class);
    Route::resource('childCategory', ChildCategoryController::class);
});

Route::get('/admin/get-subcategories/{id}', [ChildCategoryController::class, 'getSubCategories'])->name('admin.getSubCategories');

Route::middleware(['auth'])->group(function () {
    Route::get('/change-password', [PasswordChangeController::class, 'showChangeForm'])->name('password.change');
    Route::post('/change-password', [PasswordChangeController::class, 'changePassword'])->name('password.change.submit');

    Route::get('/search', [SearchPaperController::class, 'search'])->name('papers.search');
    Route::get('/papers/{id}', [SearchPaperController::class, 'show'])->name('papers.show');



    // Support Ticket
    Route::get('user/support-tickets', [SupportTicketController::class, 'userTickets'])->name('supportTickets.index');
    Route::get('user/support-tickets/create', [SupportTicketController::class, 'create'])->name('supportTickets.create');
    Route::post('user/support-tickets/store', [SupportTicketController::class, 'store'])->name('supportTickets.store');
    Route::post('user/support-tickets/{ticketId}/reply', [SupportTicketController::class, 'userReply'])->name('supportTickets.userReply');

    //FAQ
    Route::get('/faqs', [FaqPublicController::class, 'index'])->name('faqs.index');
});

//Multi-lingual support
Route::get('/lang/{lang}', function ($lang) {
    if (in_array($lang, ['en', 'fr', 'hi', 'es'])) {
        session(['locale' => $lang]);
        app()->setLocale($lang);
    }

    return redirect()->back();
})->name('change.language');
