function showLogoutConfirmationModal() {
    var modal = document.getElementById("logoutConfirmationModal");
    modal.classList.remove("hidden");
}

function hideLogoutConfirmationModal() {
    var modal = document.getElementById("logoutConfirmationModal");
    modal.classList.add("hidden");
}

function closeCreateNoteModal() {
    hideCreateNoteModal();
    location.reload();
}

function showCreateNoteModal() {
    var modal = document.getElementById("createNoteModal");
    modal.classList.remove("hidden");
}

function hideCreateNoteModal() {
    var modal = document.getElementById("createNoteModal");
    modal.classList.add("hidden");
}

function createNote() {
    const noteTitle = document.getElementById('noteTitle').value;
    const noteContent = document.getElementById('noteContent').value;
    const notePriority = document.getElementById('notePriority').value;
    const noteLink = document.getElementById('noteLink').value;

    const formData = new FormData();
    formData.append('title', noteTitle);
    formData.append('content', noteContent);
    formData.append('priority', notePriority);
    formData.append('link', noteLink);

    fetch('create_note.php', {
        method: 'POST',
        body: formData,
        credentials: 'include',
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        closeCreateNoteModal();
        
        location.reload();
    })
    .catch(error => console.error('Грешка при изпращане на заявката:', error));
}

function closeCreateNoteModal() {
    document.getElementById('createNoteModal').classList.add('hidden');
}

document.addEventListener('DOMContentLoaded', function () {
    getSortedUserNotes();
    const allButton = document.querySelector('[onclick="getSortedUserNotes()"]');
    allButton.classList.add('active');
});

document.addEventListener('DOMContentLoaded', getSortedUserNotes);
function getSortedUserNotes(priority = 'all') {
    fetch('get_user_notes.php', {
        method: 'GET',
        credentials: 'include',
    })
    .then(response => response.json())
    .then(data => {
        const userNotesContainer = document.getElementById('userNotes');
        const noNotesMessage = document.getElementById('noNotesMessage');
        userNotesContainer.innerHTML = '';
        if (data.notes && data.notes.length > 0) {
            const sortedNotes = priority === 'all' ? data.notes : data.notes.filter(note => note.priority === priority);

            sortedNotes.forEach(note => {
                const noteElement = document.createElement('div');
                noteElement.classList.add('bg-purple-100', 'rounded-md', 'shadow-md');
                const priorityLine = document.createElement('div');
                noteElement.appendChild(priorityLine);
                noteElement.innerHTML += `
                <div class="p-4 rounded-md shadow-md relative">
                    <h3 class="text-2xl font-semibold text-purple-800">${note.title}</h3>
                    <button onclick="deleteNote(${note.id})" class="mt-5 px-4 py-2 bg-purple-500 text-white rounded-md focus:outline-none">Изтрий</button>
                    <button onclick="viewNote(${note.id})" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded-md focus:outline-none">Прегледай</button>
                    <button onclick="editNote(${note.id})" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded-md focus:outline-none">Редактирай</button>
                    <button onclick="downloadNote(${note.id})" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded-md focus:outline-none">Изтегли</button>
                    <button onclick="openShareNoteModal('${window.location.origin}/${note.share_link}')" class="mt-2 px-4 py-2 bg-purple-500 text-white rounded-md focus:outline-none">Сподели</button>
                    <div class="h-full w-1 ${note.priority === 'high' ? 'bg-red-500' : note.priority === 'medium' ? 'bg-blue-500' : note.priority === 'low' ? 'bg-yellow-600' : ''} absolute left-0 top-0 bottom-0"></div>
                </div>
                `;

                userNotesContainer.appendChild(noteElement);
            });
            noNotesMessage.style.display = 'none';
        } else {
            userNotesContainer.style.display = 'none';
            noNotesMessage.style.display = 'block';
        }
    })
    .catch(error => console.error('Грешка при извличане на бележките:', error));
}

function deleteNote(noteId) {
    fetch('delete_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        credentials: 'include',
        body: JSON.stringify({ id: noteId }),
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            getSortedUserNotes();
        } else {
            console.error('Грешка при изтриване на бележката:', data.message);
        }
    })
    .catch(error => console.error('Грешка при изтриване на бележката:', error));
}

document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('allFilter').addEventListener('click', function () {
        setActiveFilter(this);
        getSortedUserNotes('all');
    });
    document.getElementById('highFilter').addEventListener('click', function () {
        setActiveFilter(this);
        getSortedUserNotes('high');
    });
    document.getElementById('mediumFilter').addEventListener('click', function () {
        setActiveFilter(this);
        getSortedUserNotes('medium');
    });
    document.getElementById('lowFilter').addEventListener('click', function () {
        setActiveFilter(this);
        getSortedUserNotes('low');
    });
    function setActiveFilter(clickedFilter) {
        const filters = document.querySelectorAll('.filter-button');
        filters.forEach(filter => filter.classList.remove('active'));
        clickedFilter.classList.add('active');
    }
    getSortedUserNotes();
});

function viewNote(noteId, shareLink) {
    const url = shareLink ? `get_note.php?id=${shareLink}` : `get_note.php?id=${noteId}`;
    
    fetch(url, {
        method: 'GET',
        credentials: 'include',
    })
    .then(response => response.json())
    .then(data => {
        showViewNoteModal(data.title, data.content);
    })
    .catch(error => console.error('Грешка при извличане на бележката:', error));
}

function showViewNoteModal(title, content) {
    var modal = document.getElementById("viewNoteModal");
    modal.querySelector('.modal-title').textContent = title;
    modal.querySelector('.modal-content').textContent = content;
    modal.classList.remove("hidden");
}

function hideViewNoteModal() {
    var modal = document.getElementById("viewNoteModal");
    modal.classList.add("hidden");
}

function editNote(noteId) {
    const editNoteModal = document.getElementById('editNoteModal');
    const editNoteTitleInput = document.getElementById('editNoteTitle');
    const editNoteContentTextarea = document.getElementById('editNoteContent');
    const editNotePrioritySelect = document.getElementById('editNotePriority');
    const editNoteIdInput = document.getElementById('editNoteId');
    fetch(`get_note.php?id=${noteId}`, {
        method: 'GET',
        credentials: 'include',
    })
    .then(response => response.json())
    .then(data => {
        editNoteTitleInput.value = data.title;
        editNoteContentTextarea.value = data.content;
        editNotePrioritySelect.value = data.priority;
        editNoteIdInput.value = noteId;
        editNoteModal.classList.remove('hidden');
    })
    .catch(error => console.error('Грешка при извличане на бележката:', error));
}

function updateNote() {
    const editNoteTitle = document.getElementById('editNoteTitle').value;
    const editNoteContent = document.getElementById('editNoteContent').value;
    const editNotePriority = document.getElementById('editNotePriority').value;
    const editNoteId = document.getElementById('editNoteId').value;
    fetch('update_note.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({
            id: `${editNoteId}&title=${editNoteTitle}`,
            title: `${editNoteTitle}`,
            content: `${editNoteContent}`,
            priority: `${editNotePriority}`
        }),
        credentials: 'include',
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        hideEditNoteModal();
        getSortedUserNotes();
        location.reload();
        
    })
    .catch(error => console.error('Грешка при актуализация на бележката:', error));
    location.reload();
}

function hideEditNoteModal() {
    const editNoteModal = document.getElementById('editNoteModal');
    editNoteModal.classList.add('hidden');
}

function openShareNoteModal(shareLink) {
    var modal = document.getElementById("shareNoteModal");
    var shareNoteLinkInput = document.getElementById("shareNoteLink");

    shareNoteLinkInput.value = shareLink;
    modal.classList.remove("hidden");
}

function closeShareNoteModal() {
    var modal = document.getElementById("shareNoteModal");
    modal.classList.add("hidden");
}

function copyToClipboard() {
    var shareNoteLink = document.getElementById("shareNoteLink");
    shareNoteLink.select();
    document.execCommand("copy");
}

function downloadNote(noteId) {
    window.location.href = `download_note.php?id=${noteId}`;
}

var createNoteButton = document.getElementById("createNoteButton");
createNoteButton.addEventListener("click", showCreateNoteModal);
