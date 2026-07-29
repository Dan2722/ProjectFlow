document.addEventListener("DOMContentLoaded", () => {
    // 1. تحديد الصفحة الحالية وتفعيل العنصر النشط في القائمة الجانبية تلقائياً
    const currentPath = window.location.pathname;
    const navLinks = document.querySelectorAll(".navigation-sidebar .nav-link");

    navLinks.forEach(link => {
        link.classList.remove("active");
        const linkHref = link.getAttribute("href");
        
        if (linkHref && currentPath.includes(linkHref)) {
            link.classList.add("active");
        }
    });

    // 2. إعداد مستمعي الأحداث للتواريخ
    const startDateInput = document.getElementById('startDateInput');
    const endDateInput = document.getElementById('endDateInput');

    if (startDateInput && endDateInput) {
        startDateInput.addEventListener('change', validateDates);
        endDateInput.addEventListener('change', validateDates);
    }

    const projectStartDateInput = document.getElementById('projectStartDateInput');
    const projectEndDateInput = document.getElementById('projectEndDateInput');

    if (projectStartDateInput && projectEndDateInput) {
        projectStartDateInput.addEventListener('change', validateDates);
        projectEndDateInput.addEventListener('change', validateDates);
    }
});

/* ==========================================
   إدارة عمليات المشاريع (Projects Operations)
========================================== */
function prepareAddProjectModal(storeUrl) {
    const modalTitle = document.getElementById('projectModalTitle');
    const projectForm = document.getElementById('projectForm');
    const methodInput = document.getElementById('projectFormMethod');

    if (modalTitle) modalTitle.innerText = "إضافة مشروع جديد";
    if (projectForm) {
        projectForm.reset();
        if (storeUrl) projectForm.action = storeUrl;
    }
    if (methodInput) methodInput.value = "POST";

    // تفريغ المدخل بالمعرف الجديد الفريد للمشاريع
    const companyInput = document.getElementById('projectCompanyNameInput');
    if (companyInput) companyInput.value = '';

    const modalEl = document.getElementById('projectModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function openEditProjectModal(button, updateUrl) {
    const projectCard = button.closest('.project-card-wrapper');
    const modalTitle = document.getElementById('projectModalTitle');
    const projectForm = document.getElementById('projectForm');
    const methodInput = document.getElementById('projectFormMethod');

    if (modalTitle) modalTitle.innerText = "تعديل المشروع";
    if (projectForm && updateUrl) projectForm.action = updateUrl;
    if (methodInput) methodInput.value = "PUT";

    if (projectCard) {
        const nameInput = document.getElementById('projectNameInput');
        // تم استخدام المعرف الفريد projectCompanyNameInput لتفادي التضارب مع مودال العملاء
        const companyInput = document.getElementById('projectCompanyNameInput');
        const descInput = document.getElementById('projectDescInput');
        const startDateInput = document.getElementById('projectStartDateInput');
        const endDateInput = document.getElementById('projectEndDateInput');
        const statusSelect = document.getElementById('projectStatusSelect');

        if (nameInput) nameInput.value = projectCard.getAttribute('data-project-name') || '';
        if (companyInput) companyInput.value = projectCard.getAttribute('data-company-name') || '';
        if (descInput) descInput.value = projectCard.getAttribute('data-project-desc') || '';
        if (startDateInput) startDateInput.value = projectCard.getAttribute('data-start-date') || '';
        if (endDateInput) endDateInput.value = projectCard.getAttribute('data-end-date') || '';
        if (statusSelect) statusSelect.value = projectCard.getAttribute('data-status') || 'قيد التنفيذ';
    }

    const modalEl = document.getElementById('projectModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function openDeleteProjectModal(button, deleteUrl) {
    const projectCard = button.closest('.project-card-wrapper');
    const projectName = projectCard ? projectCard.getAttribute('data-project-name') : '';
    
    const deleteModalText = document.getElementById('deleteProjectModalText');
    if (deleteModalText) deleteModalText.innerText = `هل تريد حذف مشروع ${projectName}؟`;

    const deleteForm = document.getElementById('deleteProjectForm');
    if (deleteForm && deleteUrl) deleteForm.action = deleteUrl;

    const modalEl = document.getElementById('deleteProjectModal');
    if (modalEl) {
        const deleteModal = new bootstrap.Modal(modalEl);
        deleteModal.show();
    }
}

/* ==========================================
   إدارة عمليات المهام (عرض الـ Modals فقط)
========================================== */
function validateDates() {
    const startDateInput = document.getElementById('startDateInput');
    const endDateInput = document.getElementById('endDateInput');
    
    if (startDateInput && endDateInput && startDateInput.value) {
        endDateInput.min = startDateInput.value;
        if (endDateInput.value && endDateInput.value < startDateInput.value) {
            endDateInput.value = startDateInput.value;
        }
    }

    const projectStartDateInput = document.getElementById('projectStartDateInput');
    const projectEndDateInput = document.getElementById('projectEndDateInput');

    if (projectStartDateInput && projectEndDateInput && projectStartDateInput.value) {
        projectEndDateInput.min = projectStartDateInput.value;
        if (projectEndDateInput.value && projectEndDateInput.value < projectStartDateInput.value) {
            projectEndDateInput.value = projectStartDateInput.value;
        }
    }
}

function prepareAddModal() {
    const modalTitle = document.getElementById('taskModalTitle');
    const taskForm = document.getElementById('taskForm');
    
    if (modalTitle) modalTitle.innerText = "اضافة مهمة";
    if (taskForm) taskForm.reset();
}

function openEditModal(button) {
    const taskCard = button.closest('.task-card');
    if (!taskCard) return;

    const taskName = taskCard.getAttribute('data-task-name') || '';
    
    const modalTitle = document.getElementById('taskModalTitle');
    const taskNameInput = document.getElementById('taskNameInput');

    if (modalTitle) modalTitle.innerText = "تعديل المهام";
    if (taskNameInput) taskNameInput.value = taskName;
    
    const modalEl = document.getElementById('taskModal');
    if (modalEl) {
        const taskModal = new bootstrap.Modal(modalEl);
        taskModal.show();
    }
}

function openDeleteModal(button) {
    const taskCard = button.closest('.task-card');
    if (!taskCard) return;

    const taskName = taskCard.getAttribute('data-task-name') || taskCard.querySelector('.task-name')?.innerText || '';
    
    const deleteModalText = document.getElementById('deleteModalText');
    if (deleteModalText) deleteModalText.innerText = `هل تريد حذف مهمة ${taskName} ؟`;

    const modalEl = document.getElementById('deleteModal');
    if (modalEl) {
        const deleteModal = new bootstrap.Modal(modalEl);
        deleteModal.show();
    }
}

function confirmDelete() {
    const deleteModalEl = document.getElementById('deleteModal');
    if (deleteModalEl) {
        const deleteModalInstance = bootstrap.Modal.getInstance(deleteModalEl);
        if (deleteModalInstance) deleteModalInstance.hide();
    }
    
    showStatusMessage("تم حذف المهمة بنجاح");
}

function handleTaskSubmit(event) {
    event.preventDefault();
    const modalTitle = document.getElementById('taskModalTitle');
    const title = modalTitle ? modalTitle.innerText : '';
    
    const taskModalEl = document.getElementById('taskModal');
    if (taskModalEl) {
        const taskModalInstance = bootstrap.Modal.getInstance(taskModalEl);
        if (taskModalInstance) taskModalInstance.hide();
    }
    
    if (title.includes("اضافة")) {
        showStatusMessage("تم إضافة المهمة بنجاح");
    } else {
        showStatusMessage("تم حفظ التعديلات بنجاح");
    }
}

/* ==========================================
   إدارة العملاء
========================================== */
let isEditMode = false;
let editingClientCard = null;

function prepareAddClientModal() {
    isEditMode = false;
    editingClientCard = null;

    const modalTitle = document.getElementById('clientModalTitle');
    const clientForm = document.getElementById('clientForm');

    if (modalTitle) modalTitle.innerText = 'إضافة عميل';
    if (clientForm) clientForm.reset();
    
    const modalEl = document.getElementById('clientModal');
    if (modalEl) {
        const clientModal = new bootstrap.Modal(modalEl);
        clientModal.show();
    }
}

function openEditClientModal(button) {
    isEditMode = true;
    editingClientCard = button.closest('.client-card-wrapper');

    const modalTitle = document.getElementById('clientModalTitle');
    if (modalTitle) modalTitle.innerText = 'تعديل بيانات العميل';

    if (editingClientCard) {
        const clientName = document.getElementById('clientNameInput');
        const companyName = document.getElementById('companyNameInput');
        const clientEmail = document.getElementById('clientEmailInput');
        const clientPhone = document.getElementById('clientPhoneInput');
        const clientProject = document.getElementById('clientProjectInput');

        if (clientName) clientName.value = editingClientCard.querySelector('.client-name')?.innerText || '';
        if (companyName) companyName.value = editingClientCard.querySelector('.company-name')?.innerText || '';
        if (clientEmail) clientEmail.value = editingClientCard.querySelector('.client-email')?.innerText || '';
        if (clientPhone) clientPhone.value = editingClientCard.querySelector('.client-phone')?.innerText || '';
        if (clientProject) clientProject.value = editingClientCard.querySelector('.client-project')?.innerText || '';
    }

    const modalEl = document.getElementById('clientModal');
    if (modalEl) {
        const modal = new bootstrap.Modal(modalEl);
        modal.show();
    }
}

function handleClientSubmit(event) {
    event.preventDefault();

    if (isEditMode && editingClientCard) {
        const clientName = document.getElementById('clientNameInput')?.value;
        const companyName = document.getElementById('companyNameInput')?.value;
        const clientEmail = document.getElementById('clientEmailInput')?.value;
        const clientPhone = document.getElementById('clientPhoneInput')?.value;
        const clientProject = document.getElementById('clientProjectInput')?.value;

        const elName = editingClientCard.querySelector('.client-name');
        const elCompany = editingClientCard.querySelector('.company-name');
        const elEmail = editingClientCard.querySelector('.client-email');
        const elPhone = editingClientCard.querySelector('.client-phone');
        const elProject = editingClientCard.querySelector('.client-project');

        if (elName && clientName) elName.innerText = clientName;
        if (elCompany && companyName) elCompany.innerText = companyName;
        if (elEmail && clientEmail) elEmail.innerText = clientEmail;
        if (elPhone && clientPhone) elPhone.innerText = clientPhone;
        if (elProject && clientProject) elProject.innerText = clientProject;

        showStatusMessage("تم حفظ التعديلات بنجاح");
    } else {
        showStatusMessage("تم إضافة العميل بنجاح");
    }

    const modalEl = document.getElementById('clientModal');
    if (modalEl) {
        const modal = bootstrap.Modal.getInstance(modalEl);
        if (modal) modal.hide();
    }
}

function openDeleteClientModal(button) {
    const clientCard = button.closest('.client-card-wrapper');
    const clientName = clientCard ? clientCard.querySelector('.client-name')?.innerText : 'العميل';
    
    const deleteModalText = document.getElementById('deleteClientModalText');
    if (deleteModalText) deleteModalText.innerText = `هل تريد حذف العميل ${clientName} ؟`;

    const modalEl = document.getElementById('deleteClientModal');
    if (modalEl) {
        const deleteModal = new bootstrap.Modal(modalEl);
        deleteModal.show();
    }
}

function confirmDeleteClient() {
    const deleteModalEl = document.getElementById('deleteClientModal');
    if (deleteModalEl) {
        const deleteModalInstance = bootstrap.Modal.getInstance(deleteModalEl);
        if (deleteModalInstance) deleteModalInstance.hide();
    }
    
    showStatusMessage("تم حذف العميل بنجاح");
}

/* ==========================================
   Login Page JS
========================================== */
document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    const usernameInput = document.getElementById('usernameInput');
    const passwordInput = document.getElementById('passwordInput');
    const rememberMeCheckbox = document.getElementById('rememberMe');
    const usernameError = document.getElementById('usernameError');
    const passwordError = document.getElementById('passwordError');

    if (usernameInput && localStorage.getItem('savedUsername')) {
        usernameInput.value = localStorage.getItem('savedUsername');
        if (rememberMeCheckbox) {
            rememberMeCheckbox.checked = true;
        }
    }

    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            resetErrors();

            const username = usernameInput ? usernameInput.value.trim() : '';
            const password = passwordInput ? passwordInput.value.trim() : '';
            const emailPattern = /^[a-zA-Z0-9._%+-]+@fvs\.com\.sa$/;

            if (!username) {
                e.preventDefault();
                showError(usernameInput, usernameError, 'يرجى إدخال اسم المستخدم');
                return;
            }

            if (!emailPattern.test(username)) {
                e.preventDefault();
                showError(usernameInput, usernameError, 'الصيغة غير صحيحة، يجب أن ينتهي البريد بـ name@fvs.com.sa');
                return;
            }

            if (!password) {
                e.preventDefault();
                showError(passwordInput, passwordError, 'يرجى إدخال كلمة المرور');
                return;
            }

            if (rememberMeCheckbox && rememberMeCheckbox.checked) {
                localStorage.setItem('savedUsername', username);
            } else {
                localStorage.removeItem('savedUsername');
            }
        });
    }

    function showError(inputEl, errorEl, message) {
        if (inputEl) inputEl.classList.add('is-invalid');
        if (errorEl) {
            errorEl.textContent = message;
            errorEl.classList.remove('d-none');
        }
    }

    function resetErrors() {
        if (usernameInput) usernameInput.classList.remove('is-invalid');
        if (passwordInput) passwordInput.classList.remove('is-invalid');
        if (usernameError) {
            usernameError.textContent = '';
            usernameError.classList.add('d-none');
        }
        if (passwordError) {
            passwordError.textContent = '';
            passwordError.classList.add('d-none');
        }
    }
});

/* ==========================================
   دالة عرض رسائل النجاح والتنبيه الموحدة
========================================== */
function showStatusMessage(message) {
    const messageElement = document.getElementById('statusModalMessage');
    if (messageElement) {
        messageElement.innerText = message;
    }
    
    const modalEl = document.getElementById('statusMessageModal');
    if (modalEl) {
        const existingModal = bootstrap.Modal.getInstance(modalEl);
        if (existingModal) {
            existingModal.hide();
        }
        
        const statusModal = new bootstrap.Modal(modalEl);
        statusModal.show();
    }
}

/* ==========================================
   إدارة الملف الشخصي
========================================== */
function handleProfileSubmit(event) {
    event.preventDefault();
    
    const phoneInput = document.getElementById('profilePhoneInput');
    const phoneRegex = /^05[0-9]{8}$/;

    if (phoneInput) {
        if (!phoneRegex.test(phoneInput.value)) {
            phoneInput.setCustomValidity("يجب أن يبدأ رقم الجوال بـ 05 ويتكون من 10 أرقام");
            phoneInput.reportValidity();
            return;
        } else {
            phoneInput.setCustomValidity("");
        }
    }
    
    const nameInput = document.getElementById('profileNameInput');
    const emailInput = document.getElementById('profileEmailInput');

    const newName = nameInput ? nameInput.value : '';
    const newEmail = emailInput ? emailInput.value : '';
    
    const nameDisplay = document.getElementById('profileCardNameDisplay');
    const emailDisplay = document.getElementById('profileCardEmailDisplay');

    if (nameDisplay) nameDisplay.innerText = newName;
    if (emailDisplay) emailDisplay.innerText = newEmail;
    
    const nameParts = newName.trim().split(' ');
    let initials = nameParts[0] ? nameParts[0][0] : '';
    if (nameParts.length > 1) {
        initials += nameParts[nameParts.length - 1][0];
    }
    const avatarEl = document.getElementById('profileCardAvatar');
    if (avatarEl) avatarEl.innerText = initials;
    
    showStatusMessage("تم حفظ التعديلات بنجاح");
}

const phoneField = document.getElementById('profilePhoneInput');
if (phoneField) {
    phoneField.addEventListener('input', function() {
        this.setCustomValidity('');
    });
}

function openDeleteAccountModal() {
    const nameInput = document.getElementById('profileNameInput');
    const accountName = (nameInput && nameInput.value.trim() !== "") ? nameInput.value : "المستخدم"; 
    
    const deleteText = document.getElementById('deleteAccountModalText');
    if (deleteText) deleteText.innerText = `هل تريد حذف حساب ${accountName} ؟`;
    
    const modalEl = document.getElementById('deleteAccountModal');
    if (modalEl) {
        const deleteModal = new bootstrap.Modal(modalEl);
        deleteModal.show();
    }
}

function confirmDeleteAccount() {
    const deleteModalEl = document.getElementById('deleteAccountModal');
    if (deleteModalEl) {
        const deleteModalInstance = bootstrap.Modal.getInstance(deleteModalEl);
        if (deleteModalInstance) deleteModalInstance.hide();
    }
    
    showStatusMessage("تم حذف الحساب بنجاح");
}

/* ==========================================
   إعدادات اللغة والترجمة
========================================== */
const translations = {
    ar: {
        pageTitle: "الاعدادات",
        navHome: "الصفحة الرئيسية",
        navProjects: "المشاريع",
        navTasks: "المهام",
        navClients: "العملاء",
        navSettings: "الاعدادت",
        navLogout: "تسجيل خروج",
        welcomeMessage: "مرحبا دان!",
        notificationsTitle: "الإشعارات",
        notificationsNew: "إشعارين جديدين",
        notif1Title: "تحديث المشروع الجديد",
        notif1Time: "منذ 5 دقايق",
        notif1Desc: "تم اضافة مهام جديدة في مشروع نظرة الحلول المستقبل.",
        notif2Title: "تنبيه النظام",
        notif2Time: "منذ ساعة",
        notif2Desc: "تم تحديث كلمة المرور بنجاح.",
        noMoreNotif: "لا توجد إشعارات أخرى",
        settingsPageHeader: "الاعدادات",
        settingsPageSub: "إدارة تفضيلاتك وحسابك الشخصي",
        notificationsHeading: "الاشعارات",
        notificationsSub: "إشعارات البريد الإلكتروني",
        toggleEmailNotif: "تفعيل إشعارات البريد",
        languagesHeading: "اللغات",
        languagesSub: "لغة الواجهة",
        currentLangText: "العربية",
        changePassHeading: "تغيير كلمة المرور",
        changePassSub: "تحديث بيانات تسجيل الدخول الخاصة بك",
        currentPassLabel: "كلمة المرور الحالية",
        newPassLabel: "كلمة المرور الجديدة",
        confirmPassLabel: "تأكيد كلمة المرور الجديدة",
        updatePassBtn: "تحديث كلمة المرور",
        okBtn: "حسناً",
        passSuccessMsg: "تم تحديث كلمة المرور بنجاح"
    },
    en: {
        pageTitle: "Settings",
        navHome: "Home",
        navProjects: "Projects",
        navTasks: "Tasks",
        navClients: "Clients",
        navSettings: "Settings",
        navLogout: "Logout",
        welcomeMessage: "Welcome Dan!",
        notificationsTitle: "Notifications",
        notificationsNew: "2 New Notifications",
        notif1Title: "New Project Update",
        notif1Time: "5 mins ago",
        notif1Desc: "New tasks have been added to Future Solutions project.",
        notif2Title: "System Alert",
        notif2Time: "1 hour ago",
        notif2Desc: "Password updated successfully.",
        noMoreNotif: "No more notifications",
        settingsPageHeader: "Settings",
        settingsPageSub: "Manage your preferences and personal account",
        notificationsHeading: "Notifications",
        notificationsSub: "Email notifications",
        toggleEmailNotif: "Enable Email Notifications",
        languagesHeading: "Languages",
        languagesSub: "Interface language",
        currentLangText: "English",
        changePassHeading: "Change Password",
        changePassSub: "Update your login credentials",
        currentPassLabel: "Current Password",
        newPassLabel: "New Password",
        confirmPassLabel: "Confirm New Password",
        updatePassBtn: "Update Password",
        okBtn: "OK",
        passSuccessMsg: "Password updated successfully"
    }
};

function changeLanguage(lang) {
    localStorage.setItem('preferredLang', lang);

    const htmlRoot = document.getElementById('htmlRoot');
    const bootstrapCSS = document.getElementById('bootstrapCSS');
    const langBadge = document.getElementById('currentLangBadge');

    if (htmlRoot) {
        if (lang === 'en') {
            htmlRoot.setAttribute('lang', 'en');
            htmlRoot.setAttribute('dir', 'ltr');
            if (bootstrapCSS) {
                bootstrapCSS.href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css";
            }
            if (langBadge) langBadge.textContent = translations.en.currentLangText;
        } else {
            htmlRoot.setAttribute('lang', 'ar');
            htmlRoot.setAttribute('dir', 'rtl');
            if (bootstrapCSS) {
                bootstrapCSS.href = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css";
            }
            if (langBadge) langBadge.textContent = translations.ar.currentLangText;
        }
    }

    const elementsToTranslate = document.querySelectorAll('[data-i18n]');
    elementsToTranslate.forEach(element => {
        const key = element.getAttribute('data-i18n');
        if (translations[lang] && translations[lang][key]) {
            element.textContent = translations[lang][key];
        }
    });

    updateActiveLanguageButtons(lang);
}

function updateActiveLanguageButtons(lang) {
    const arBtn = document.getElementById('langArBtn');
    const enBtn = document.getElementById('langEnBtn');

    if (arBtn && enBtn) {
        if (lang === 'ar') {
            arBtn.classList.add('bg-light', 'border-secondary');
            enBtn.classList.remove('bg-light', 'border-secondary');
        } else {
            enBtn.classList.add('bg-light', 'border-secondary');
            arBtn.classList.remove('bg-light', 'border-secondary');
        }
    }
}

function handlePasswordChange(event) {
    event.preventDefault();

    const currentPass = document.getElementById('currentPassInput');
    const newPass = document.getElementById('newPassInput');
    const confirmPass = document.getElementById('confirmPassInput');

    if (!newPass || !confirmPass) return;

    confirmPass.setCustomValidity('');

    const newPassValue = newPass.value.trim();
    const confirmPassValue = confirmPass.value.trim();

    if (newPassValue !== confirmPassValue) {
        const currentLang = localStorage.getItem('preferredLang') || 'ar';
        const errorMsg = currentLang === 'en' 
            ? 'Passwords do not match.' 
            : 'كلمتا المرور غير متطابقتين.';

        confirmPass.setCustomValidity(errorMsg);
        confirmPass.reportValidity();
        return;
    }

    const statusModalEl = document.getElementById('statusMessageModal');
    if (statusModalEl) {
        const modalInstance = new bootstrap.Modal(statusModalEl);
        modalInstance.show();
    }

    const form = document.getElementById('passwordChangeForm');
    if (form) form.reset();
}

document.addEventListener('DOMContentLoaded', () => {
    const confirmPass = document.getElementById('confirmPassInput');
    if (confirmPass) {
        confirmPass.addEventListener('input', () => {
            confirmPass.setCustomValidity('');
        });
    }

    const savedLang = localStorage.getItem('preferredLang') || 'ar';
    changeLanguage(savedLang);
});