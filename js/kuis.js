function showQuiz() {
    document.getElementById('quizOverlay').classList.remove('hidden');
    document.getElementById('leaderboardSection').classList.add('hidden');
}

function hideQuiz() {
    document.getElementById('quizOverlay').classList.add('hidden');
}