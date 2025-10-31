<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Reace Ian Roeloffze">
    <meta name="description" content="A full-stack quiz app.">
    <title>Document</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-blue-200 py-12">
    <!-- Create the quiz container -->
     <div class="quiz-container @apply bg-white p-8 rounded shadow-lg max-w-lg mx-auto">
        <h1 class="quiz-title @apply text-4xl font-bold mb-6">Quiz App</h1>
        <div class="question-section ">
            <!-- Content will be rendered dynamically -->
        </div>
        <button class="next-btn @apply bg-blue-500 px-5 py-3 rounded text-white hover:bg-blue-700 hidden">Next</button>
     </div>
     <div class="result-section @apply mt-4"></div>

     <script>
        // Fetch questions from the JSON file
        const questions = <?php echo file_get_contents('questions.json'); ?>;
        // Start with the first question
        let currentQuestionIndex = 0;
        // Starting score
        let score = 0;

        // Create a function to display the current question
        function renderQuestion(index) {
            // Retrieve necessary DOM elements
            const questionSection = document.querySelector('.question-section');
            const nextButton = document.querySelector('.next-btn');
            const resultSection = document.querySelector('.result-section');

            // Store question data
            const questionData = questions[index];
            // Render the question
            const questionHtml = `<h2 class="question-text @apply text-2xl mb-4">${questionData.question}</h2>`;
            questionSection.innerHTML = questionHtml;
            // Render the options
            const options = questionData.options;
            options.forEach((option, i) => {
                const optionHtml = `<label class="option @apply block mb-2 cursor-pointer bg-gray-100 p-2 rounded hover:bg-gray-200">
                    <input type="checkbox" name="option" value="${i}" class="mr-2">
                    ${option}`
                questionSection.innerHTML += optionHtml;
            });

        }

        renderQuestion(currentQuestionIndex);






     </script>
</body>
</html>