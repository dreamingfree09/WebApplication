// All the questions and options
var quizQuestions = null
var selectedQuestions = null

// Fetch quiz questions from the server
fetch('../class/getQuiz.php')
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok')
        }
        return response.json()
    })
    .then(data => {
        quizQuestions = data
        selectedQuestions = selectRandomQuestions([...quizQuestions], 10)
    })
    .catch(error => {
        console.error('' + error + '', error)
    })

var currentQuestionIndex = 0
var correctCount = 0
var wrongCount = 0
var incompleteCount = 0
var timer

// Function to update the progress display
function updateProgress() {
    const progressElement = document.getElementById('quizProgress')
    progressElement.textContent = `Question ${currentQuestionIndex + 1} of ${
        selectedQuestions.length
    }`
}

// Function to select random questions from the array
function selectRandomQuestions(questions, count) {
    // First, shuffle the entire array of questions
    shuffleArray(questions);

    // Then, slice the first 'count' questions from the shuffled array
    let selected = questions.slice(0, count);

    // Shuffle the options for each selected question
    return selected.map(question => {
        let shuffledOptions = shuffleArray([...question.options]);
        return { ...question, shuffledOptions };
    });
}

// Function to shuffle an array
function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1))
        ;[array[i], array[j]] = [array[j], array[i]]
    }
    return array
}

// Function to display the current question
function displayCurrentQuestion() {
    clearInterval(timer)
    const question = selectedQuestions[currentQuestionIndex]
    const questionContainer =
        document.getElementsByClassName('question-container')[0]
    questionContainer.setAttribute('id', `question-${question.id}`)
    questionContainer.style.display = 'block'
    questionContainer.innerHTML = createQuestionHtml(question)
    startTimer()
    updateProgress()
    enableNextButton(false)
}

// Function to create HTML for a single quiz question
function createQuestionHtml(question) {
    let htmlContent = `<h3>${question.question}</h3><ul>`

    question.shuffledOptions.forEach((option, index) => {
        let optionId = `option-${question.id}-${index}`
        htmlContent += `<li>
            <input type="radio" id="${optionId}" name="answer-${question.id}" value="${index}" onchange="answerSelected('${option}', ${question.id})">
            <label for="${optionId}">${option}</label>
        </li>`
    })

    htmlContent += `</ul>`
    return htmlContent
}

// Function to handle answer selection
window.answerSelected = function (optionValue, questionId) {
    const question = selectedQuestions.find(q => q.id === questionId)
    clearInterval(timer)

    const selectedOption = document.querySelector(
        `input[name='answer-${questionId}']:checked`
    )
    const optionsList = document.querySelectorAll(
        `input[name='answer-${questionId}']`
    )
    optionsList.forEach(option => (option.disabled = true)) // Disable all options
    if (question.correctAnswer === optionValue) {
        correctCount++
        selectedOption.parentElement.classList.add('correct-answer')
    } else {
        wrongCount++
        selectedOption.parentElement.classList.add('wrong-answer')
    }

    enableNextButton(true)
}

// Function to enable or disable the Next button
function enableNextButton(enabled) {
    const nextButton = document.getElementById('nextQuestion')
    nextButton.disabled = !enabled
}

// Timer and its implementation
function startTimer() {
    let timeLeft = 15
    const timerElement = document.getElementById('timer')
    timerElement.textContent = `Time left: ${timeLeft} seconds`

    timer = setInterval(() => {
        timeLeft--
        timerElement.textContent = `Time left: ${timeLeft} seconds`

        if (timeLeft <= 0) {
            clearInterval(timer)
            timerElement.textContent = 'Time is up!'
            incompleteCount++
            goToNextQuestion()
        }
    }, 1000)
}

// Function to navigate to the next question
function goToNextQuestion() {
    if (currentQuestionIndex < selectedQuestions.length - 1) {
        currentQuestionIndex++
        displayCurrentQuestion()
    } else {
        clearInterval(timer)
        displayResults()
    }
}

// Function to display quiz results
function displayResults() {
    const quizResults = document.getElementById('quiz-results')
    quizResults.innerHTML = `<h3>Quiz Completed!</h3>
        <p>Correct Answers: ${correctCount}</p>
        <p>Wrong Answers: ${wrongCount}</p>
        <p>Incomplete Answers: ${incompleteCount}</p>`
    quizResults.style.display = 'block'
    document.getElementById('quizForm').style.display = 'none';

    var datas = {
        correctCount: correctCount,
        wrongCount: wrongCount,
        incompleteCount: incompleteCount
    };

    // Send quiz results to the server
    fetch('../class/postQuizResult.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(datas),
    })
        .then(response => response.json())
        .then(data => {
            console.log('Data saved:', data);
        })
        .catch(error => {
            console.error('Error saving data:', error);
        });
}

// Function to initialize the quiz
function initializeQuiz() {
    currentQuestionIndex = 0
    correctCount = 0
    wrongCount = 0
    incompleteCount = 0
    document.getElementById('quizForm').style.display = 'block'
    document.getElementById('quiz-results').style.display = 'none'
    selectedQuestions = selectRandomQuestions([...quizQuestions], 10)
    displayCurrentQuestion()
    document.getElementById('nextQuestion').style.display = 'block'
    document.getElementById('startQuiz').textContent = 'Restart Quiz'
}

// Event listener for starting the quiz
document.getElementById('startQuiz').addEventListener('click', initializeQuiz)

// Event listener for the Next button
document.getElementById('quizForm').addEventListener('click', event => {
    if (event.target && event.target.id === 'nextQuestion') {
        goToNextQuestion()
    }
})

// Set the current year in the footer
document.getElementById('currentYear').textContent = new Date().getFullYear()
