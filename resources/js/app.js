import './bootstrap';

const taskSearch = document.getElementById('task-search');

const taskLoading = document.getElementById('task-loading');
const taskError = document.getElementById('task-error');
const taskEmpty = document.getElementById('task-empty');

const taskTableWrapper = document.getElementById('task-table-wrapper');
const taskMobileWrapper = document.getElementById('task-mobile-wrapper');
const taskTableBody = document.getElementById('task-table-body');

const taskPagination = document.getElementById('task-pagination');
const retryButton = document.getElementById('retry-button');

let currentSearch = '';
let currentPage = 1;

function showTaskLoading() {
    hideTaskStates();
    taskLoading?.classList.remove('hidden');
}

function showTaskError() {
    hideTaskStates();
    taskError?.classList.remove('hidden');
}

function showTaskEmpty() {
    hideTaskStates();
    taskEmpty?.classList.remove('hidden');
}

function showTaskContent() {
    hideTaskStates();
    taskTableWrapper?.classList.remove('hidden');
    taskMobileWrapper?.classList.remove('hidden');
}

function hideTaskStates() {
    taskLoading?.classList.add('hidden');
    taskError?.classList.add('hidden');
    taskEmpty?.classList.add('hidden');
    taskTableWrapper?.classList.add('hidden');
    taskMobileWrapper?.classList.add('hidden');
}

function formatStatus(status) {
    if (status === 'completed') {
        return `
            <span class="rounded-full bg-emerald-50 px-3 py-1 text-xs font-medium text-emerald-600">
                Completed
            </span>
        `;
    }

    if (status === 'in_progress') {
        return `
            <span class="rounded-full bg-violet-50 px-3 py-1 text-xs font-medium text-violet-600">
                In Progress
            </span>
        `;
    }

    return `
        <span class="rounded-full bg-amber-50 px-3 py-1 text-xs font-medium text-amber-600">
            Pending
        </span>
    `;
}

function formatPriority(priority) {
    let className = 'text-slate-500';

    if (priority === 'high') {
        className = 'text-red-600';
    } else if (priority === 'medium') {
        className = 'text-amber-600';
    }

    return `<span class="text-sm font-medium ${className}">${capitalize(priority)}</span>`;
}

function capitalize(value) {
    if (!value) {
        return '';
    }

    return value.charAt(0).toUpperCase() + value.slice(1);
}

// Was called from renderTasks() but never defined, so any table render
// with real rows threw "formatDate is not defined" and fell into the
// catch block, surfacing as showTaskError() even though the fetch succeeded.
function formatDate(value) {
    if (!value) {
        return '';
    }

    const date = new Date(value);

    if (Number.isNaN(date.getTime())) {
        return '';
    }

    return date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
    });
}

function renderTasks(tasks) {
    taskTableBody.innerHTML = '';
    taskMobileWrapper.innerHTML = '';

    tasks.forEach(task => {
        const row = document.createElement('tr');
        row.className = 'transition hover:bg-slate-50';
        row.innerHTML = `
            <td class="px-6 py-4">
                <div>
                    <p class="font-medium text-slate-900">${escapeHtml(task.title)}</p>
                    <p class="mt-1 max-w-xs truncate text-xs text-slate-500">${escapeHtml(task.description ?? '')}</p>
                </div>
            </td>
            <td class="px-6 py-4">${formatStatus(task.status)}</td>
            <td class="px-6 py-4">${formatPriority(task.priority)}</td>
            <td class="whitespace-nowrap px-6 py-4 text-sm text-slate-500">${formatDate(task.created_at)}</td>
        `;
        taskTableBody.appendChild(row);

        const card = document.createElement('div');
        card.className = 'p-5';
        card.innerHTML = `
            <div class="flex items-start justify-between gap-4">
                <div class="min-w-0">
                    <h3 class="truncate font-medium text-slate-900">${escapeHtml(task.title)}</h3>
                    <p class="mt-1 line-clamp-2 text-sm text-slate-500">${escapeHtml(task.description ?? '')}</p>
                </div>
                <div class="shrink-0">${formatStatus(task.status)}</div>
            </div>
            <div class="mt-4 flex items-center justify-between text-xs text-slate-500">
                <span>Priority: ${formatPriority(task.priority)}</span>
                <span>${formatDate(task.created_at)}</span>
            </div>
        `;
        taskMobileWrapper.appendChild(card);
    });
}

function escapeHtml(value) {
    const div = document.createElement('div');
    div.textContent = value ?? '';
    return div.innerHTML;
}

async function loadTasks(page = 1) {
    showTaskLoading();
    currentPage = page;

    try {
        const params = new URLSearchParams({ page });

        if (currentSearch.trim() !== '') {
            params.append('search', currentSearch.trim());
        }

        const response = await fetch(`/api/tasks?${params.toString()}`);

        if (!response.ok) {
            throw new Error(`HTTP error: ${response.status}`);
        }

        const result = await response.json();
        const tasks = result.data?.data ?? [];

        if (tasks.length === 0) {
            showTaskEmpty();
            renderPagination(null);
            return;
        }

        renderTasks(tasks);
        renderPagination(result.data);
        showTaskContent();
    } catch (error) {
        console.error('Failed to load tasks:', error);
        showTaskError();
    }
}

function renderPagination(meta) {
    if (!meta || meta.last_page <= 1) {
        taskPagination.innerHTML = '';
        taskPagination.classList.add('hidden');
        return;
    }

    taskPagination.classList.remove('hidden');

    let html = `
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <p class="text-sm text-slate-500">
                Showing ${meta.from ?? 0}-${meta.to ?? 0} of ${meta.total} tasks
            </p>
            <div class="flex items-center gap-1">
    `;

    if (meta.current_page > 1) {
        html += `
            <button type="button" data-page="${meta.current_page - 1}"
                class="pagination-button rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                Previous
            </button>
        `;
    }

    for (let page = 1; page <= meta.last_page; page++) {
        const active = page === meta.current_page;

        html += `
            <button type="button" data-page="${page}"
                class="pagination-button rounded-lg px-3 py-2 text-sm focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 ${
                    active ? 'bg-blue-600 text-white' : 'border border-slate-200 hover:bg-slate-50'
                }">
                ${page}
            </button>
        `;
    }

    if (meta.current_page < meta.last_page) {
        html += `
            <button type="button" data-page="${meta.current_page + 1}"
                class="pagination-button rounded-lg border border-slate-200 px-3 py-2 text-sm hover:bg-slate-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500">
                Next
            </button>
        `;
    }

    html += `
            </div>
        </div>
    `;

    taskPagination.innerHTML = html;

    document.querySelectorAll('.pagination-button').forEach(button => {
        button.addEventListener('click', () => loadTasks(Number(button.dataset.page)));
    });
}

let searchTimeout;

taskSearch?.addEventListener('input', event => {
    clearTimeout(searchTimeout);
    currentSearch = event.target.value;
    searchTimeout = setTimeout(() => loadTasks(1), 400);
});

retryButton?.addEventListener('click', () => loadTasks(currentPage));

if (taskTableBody) {
    loadTasks();
}

// Mobile sidebar: the toggle button in the header previously had nothing
// listening for its clicks, so it couldn't actually open anything.
const sidebar = document.getElementById('sidebar');
const sidebarOverlay = document.getElementById('sidebar-overlay');
const sidebarToggle = document.getElementById('sidebar-toggle');
const sidebarClose = document.getElementById('sidebar-close');

function openSidebar() {
    sidebar?.classList.remove('-translate-x-full');
    sidebarOverlay?.classList.remove('hidden');
}

function closeSidebar() {
    sidebar?.classList.add('-translate-x-full');
    sidebarOverlay?.classList.add('hidden');
}

sidebarToggle?.addEventListener('click', openSidebar);
sidebarClose?.addEventListener('click', closeSidebar);
sidebarOverlay?.addEventListener('click', closeSidebar);

sidebar?.querySelectorAll('a').forEach(link => {
    link.addEventListener('click', () => {
        if (window.innerWidth < 1024) {
            closeSidebar();
        }
    });
});

// Notifications panel: toggle on click, close on outside click or Escape.
const notifToggle = document.getElementById('notif-toggle');
const notifPanel = document.getElementById('notif-panel');

notifToggle?.addEventListener('click', event => {
    event.stopPropagation();
    notifPanel?.classList.toggle('hidden');
});

document.addEventListener('click', event => {
    if (!notifPanel || notifPanel.classList.contains('hidden')) {
        return;
    }

    if (!notifPanel.contains(event.target) && event.target !== notifToggle) {
        notifPanel.classList.add('hidden');
    }
});

document.addEventListener('keydown', event => {
    if (event.key !== 'Escape') {
        return;
    }

    closeSidebar();
    notifPanel?.classList.add('hidden');
});

const createTaskModal = document.getElementById('create-task-modal');
const openCreateTaskButton = document.getElementById('open-create-task');
const closeCreateTaskButton = document.getElementById('close-create-task');
const cancelCreateTaskButton = document.getElementById('cancel-create-task');

const createTaskForm = document.getElementById('create-task-form');
const submitCreateTaskButton = document.getElementById('submit-create-task');

const createTitle = document.getElementById('create-title');
const createDescription = document.getElementById('create-description');
const createStatus = document.getElementById('create-status');
const createPriority = document.getElementById('create-priority');

const createTaskError = document.getElementById('create-task-error');
const createTitleError = document.getElementById('create-title-error');
const createDescriptionError = document.getElementById('create-description-error');

function openCreateTaskModal() {
    createTaskModal?.classList.remove('hidden');
    createTaskModal?.classList.add('flex');

    createTitle?.focus();
}

function closeCreateTaskModal() {
    createTaskModal?.classList.add('hidden');
    createTaskModal?.classList.remove('flex');

    createTaskForm?.reset();

    createStatus.value = 'pending';
    createPriority.value = 'medium';

    clearCreateTaskErrors();
}

function clearCreateTaskErrors() {
    createTaskError?.classList.add('hidden');
    createTaskError.textContent = '';

    createTitleError?.classList.add('hidden');
    createTitleError.textContent = '';

    createDescriptionError?.classList.add('hidden');
    createDescriptionError.textContent = '';
}

function showCreateTaskErrors(errors) {
    clearCreateTaskErrors();

    if (errors.title) {
        createTitleError.textContent = errors.title[0];
        createTitleError.classList.remove('hidden');
    }

    if (errors.description) {
        createDescriptionError.textContent = errors.description[0];
        createDescriptionError.classList.remove('hidden');
    }
}

openCreateTaskButton?.addEventListener('click', () => {
    openCreateTaskModal();
});

closeCreateTaskButton?.addEventListener('click', () => {
    closeCreateTaskModal();
});

cancelCreateTaskButton?.addEventListener('click', () => {
    closeCreateTaskModal();
});

createTaskModal?.addEventListener('click', event => {
    if (event.target === createTaskModal) {
        closeCreateTaskModal();
    }
});

createTaskForm?.addEventListener('submit', async event => {
    event.preventDefault();

    clearCreateTaskErrors();

    submitCreateTaskButton.disabled = true;
    submitCreateTaskButton.textContent = 'Creating...';

    // Read from the form's hidden input (set server-side from auth()->id())
    // instead of hardcoding 1, so tasks attach to the logged-in user.
    const payload = {
        user_id: createTaskForm.querySelector('input[name="user_id"]').value,
        title: createTitle.value.trim(),
        description: createDescription.value.trim(),
        status: createStatus.value,
        priority: createPriority.value,
    };

    try {
        const response = await fetch('/api/tasks', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify(payload),
        });

        const result = await response.json();

        if (response.status === 422) {
            showCreateTaskErrors(result.errors ?? {});
            return;
        }

        if (!response.ok) {
            throw new Error(result.message ?? 'Failed to create task.');
        }

        closeCreateTaskModal();

        await loadTasks(1);

        showSuccessMessage('Task created successfully.');

    } catch (error) {
        console.error('Create task error:', error);

        createTaskError.textContent =
            error.message || 'Failed to create task.';

        createTaskError.classList.remove('hidden');

    } finally {
        submitCreateTaskButton.disabled = false;
        submitCreateTaskButton.textContent = 'Create Task';
    }
});

function showSuccessMessage(message) {
    const toast = document.getElementById('success-toast');
    const toastMessage = document.getElementById('success-toast-message');

    if (!toast || !toastMessage) {
        return;
    }

    toastMessage.textContent = message;

    toast.classList.remove('hidden');

    setTimeout(() => {
        toast.classList.add('hidden');
    }, 3000);
}

