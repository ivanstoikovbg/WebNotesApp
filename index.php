<?php
session_start();

if (!isset($_SESSION["username"])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Начална страница</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
<nav class="bg-purple-500 p-4">
    <div class="container mx-auto flex items-center justify-center">
        <div class="flex items-center space-x-4">
            <a href="index.php" class="text-white py-2 px-4 rounded-md flex items-center transition duration-300 hover:bg-purple-700 hover:text-gray-300">
                <span class="mr-2">
                    <i class="fas fa-sticky-note"></i>
                </span>
                Бележки
            </a>
            <a href="#" onclick="showLogoutConfirmationModal()" class="text-white py-2 px-4 rounded-md flex items-center transition duration-300 hover:bg-purple-700 hover:text-gray-300">
                <span class="mr-2">
                    <i class="fas fa-user"></i>
                </span>
                Изход от акаунт
            </a>
        </div>
    </div>
</nav>
<div class="container mx-auto mt-8 p-8 bg-white rounded-md shadow-md">
        <h2 class="text-3xl font-extrabold mb-6 text-center text-purple-600">Добре Дошъл/а, <?php echo $_SESSION["username"]; ?></h2>
        <p class="text-gray-700"><strong>Описание на платформата:</strong></p>
        <div class="flex justify-center mt-8">
    </div>
</div>
<div class="container mx-auto my-10 bg-white p-8 rounded-md shadow-md">
    <h2 class="text-3xl font-bold text-purple-600 mb-6">Вашите бележки:</h2>
        <div class="my-4">
        <button onclick="getSortedUserNotes('all')" id="allFilter" class="px-4 py-2 bg-purple-500 text-white rounded-md focus:outline-none active">Всички</button>
        <button onclick="getSortedUserNotes('high')" id="highFilter" class="px-4 py-2 bg-red-500 text-white rounded-md focus:outline-none">Важни</button>
        <button onclick="getSortedUserNotes('medium')" id="mediumFilter" class="px-4 py-2 bg-blue-500 text-white rounded-md focus:outline-none">Нормални</button>
        <button onclick="getSortedUserNotes('low')" id="lowFilter" class="px-4 px-2 py-2 bg-yellow-600 text-white rounded-md focus:outline-none">Неважни</button>
    </div>
    <div id="userNotes" class="space-y-4"></div>
    <p id="noNotesMessage" class="mt-4 text-gray-500 text-center">Няма създадени бележки...</p>
</div>
<div id="note-details">
</div>
<div id="createNoteButton" class="fixed bottom-7 right-4 bg-purple-500 text-white p-4 rounded-full cursor-pointer w-15 h-15">
    <i class="fas fa-plus"></i>
</div>
<div id="createNoteModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div style="width: 900px;" class="bg-white p-8 rounded-md shadow-md h-220 overflow-y-auto relative">
        <button onclick="closeCreateNoteModal()" class="absolute top-4 right-4 text-gray-700 hover:text-gray-900">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h2 class="text-3xl font-semibold text-purple-600 mb-4">Създайте бележка</h2>
        <form>
            <div class="mb-4">
                <label for="noteTitle" class="block text-gray-700 text-lg font-semibold mb-2">Заглавие:</label>
                <input type="text" id="noteTitle" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" style="min-height: 45px; max-height: 45px; overflow-y: auto; resize: none;" maxlength="50">
            </div>
            <div class="mb-4">
                <label for="noteContent" class="block text-gray-700 text-lg font-semibold mb-2">Съдържание:</label>
                <textarea id="noteContent" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" rows="10" style="min-height: 300px; max-height: 300px; overflow-y: auto; resize: none;" maxlength="1000"></textarea>
            </div>
            <div class="mb-4">
                <label for="noteLink" class="block text-gray-700 text-lg font-semibold mb-2">Линк:</label>
                <input type="text" id="noteLink" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" maxlength="255">
            </div>
            <div class="mb-4">
                <label for="notePriority" class="block text-gray-700 text-lg font-semibold mb-2">Приоритет:</label>
                <select id="notePriority" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500">
                    <option value="low">Неважни</option>
                    <option value="medium">Нормална</option>
                    <option value="high">Важна</option>
                </select>
            </div>
            <button type="button" onclick="createNote()" class="bg-purple-500 text-white py-3 px-6 rounded-md hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple">Създай бележка</button>
        </form>
    </div>
</div>
<div id="viewNoteModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div style="width: 600px;" class="bg-white p-8 rounded-md shadow-md h-220 overflow-y-auto relative">
        <button onclick="hideViewNoteModal()" class="absolute top-4 right-4 text-gray-700 hover:text-gray-900">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h2 class="text-3xl font-semibold text-purple-600 mb-4 modal-title" style="word-wrap: break-word;"></h2>
        <div class="modal-content overflow-auto" style="word-wrap: break-word;">
        <p class="note-link"></p>
        </div>
    </div>
</div>
<div id="editNoteModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div style="width: 900px;" class="bg-white p-8 rounded-md shadow-md h-220 overflow-y-auto relative">
        <button onclick="closeEditNoteModal()" class="absolute top-4 right-4 text-gray-700 hover:text-gray-900">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h2 class="text-3xl font-semibold text-purple-600 mb-4">Редактирай бележка</h2>
        <form>
            <input type="hidden" id="editNoteId">
            <div class="mb-4">
                <label for="editNoteTitle" class="block text-gray-700 text-lg font-semibold mb-2">Заглавие:</label>
                <input type="text" id="editNoteTitle" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500">
            </div>
            <div class="mb-4">
                <label for="editNoteContent" class="block text-gray-700 text-lg font-semibold mb-2">Съдържание:</label>
                <textarea id="editNoteContent" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" rows="15"></textarea>
            </div>
            <div class="mb-4">
                <label for="editNotePriority" class="block text-gray-700 text-lg font-semibold mb-2">Приоритет:</label>
                <select id="editNotePriority" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500">
                    <option value="low">Неважни</option>
                    <option value="medium">Нормални</option>
                    <option value="high">Важни</option>
                </select>
            </div>
            <button type="button" onclick="updateNote()" class="bg-purple-500 text-white py-3 px-6 rounded-md hover:bg-purple-600 focus:outline-none focus:shadow-outline-purple">Запази промените</button>
        </form>
    </div>
</div>
<div id="shareNoteModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div style="width: 600px;" class="bg-white p-8 rounded-md shadow-md h-220 relative">
        <button onclick="closeShareNoteModal()" class="absolute top-4 right-4 text-gray-700 hover:text-gray-900">
            <i class="fas fa-times text-xl"></i>
        </button>
        <h2 class="text-3xl font-semibold text-purple-600 mb-4">Споделяне на бележка</h2>
        <div class="mb-4 relative">
            <label for="shareNoteLink" class="block text-gray-700 text-lg font-semibold mb-1">Линк за споделяне:</label>
            <div class="relative flex items-center">
                <input type="text" id="shareNoteLink" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:border-purple-500" readonly>
                <button onclick="copyToClipboard()" class="absolute top-0.1 right-3 p-3 text-gray-700 hover:text-purple-600 focus:outline-none">
                    <i class="far fa-copy text-2xl"></i>
                </button>
            </div>
        </div>
    </div>
</div>
<div id="logoutConfirmationModal" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex items-center justify-center hidden">
    <div class="bg-white p-8 rounded-md shadow-md">
        <p class="text-lg text-gray-700">Сигурни ли сте, че искате да излезете от акаунта си?</p>
        <div class="mt-4 flex justify-end">
            <button onclick="hideLogoutConfirmationModal()" class="mr-4 text-gray-500 hover:text-gray-700">Не</button>
            <a href="logout.php" class="bg-purple-500 text-white py-2 px-4 rounded-md hover:bg-purple-700 focus:outline-none focus:shadow-outline-red">Да</a>
        </div>
    </div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
<script src="/assets/js/main.js"></script>
</body>
</html>