<?php
session_start();
if (!isset($_SESSION["username"])) {
    header("Location: login.html");
    exit();
}
$username = $_SESSION['username'];
$age = $_SESSION['age'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>FitTrack - Health</title>
    <link rel="stylesheet" href="meal2.css?v=<?php echo time(); ?>">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="health.css">
</head>
<body>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <button id="closeSidebar">&times;</button>
    <div class="user-profile-sidebar">
        <div class="top-profile-sidebar">
            <img class="mainProfileImg-sidebar" src="svg/profile.svg" alt="Profile Image">
            <div class="name-age-sidebar">
                <h2 class="username"><?php echo htmlspecialchars($username); ?></h2>
                <p class="gender-age"><?php echo htmlspecialchars($age); ?> years old</p>
            </div>
        </div>
    </div>
    <ul class="menuList">
        <li><a href="home.php"><img src="svg/home.svg" alt="Home"> Home</a></li>
        <li><a href="Meal.html"><img src="svg/meals.svg" alt="Meals"> Meals</a></li>
        <li><a href="weekexercise.html"><img src="svg/dumbell.svg" alt="Exercises"> Exercises</a></li>
        <li><a href="achievements.html"><img src="svg/medal.svg" alt="Achievements"> Achievements</a></li>
        <li><a href="settings.html"><img src="svg/settings.svg" alt="Settings"> Settings</a></li>
    </ul>
</div>

<!-- Navbar -->
<nav class="navbar navbar-light bg-light px-3 d-flex justify-content-between align-items-center">
    <div class="navbar-brand">
        <div class="menuIcon" id="menuIcon"><img src="svg/hamburger.svg" alt=""></div>
        <div class="mainName">FitTrack - Meals</div>
    </div>
    <div class="nav-right">
        <img src="svg/bell.svg" alt="">
        <img src="svg/dark.svg" alt="">
        <form method="post">
            <button type="submit" name="logout" class="btn btn-danger">Logout</button>
        </form>
    </div>
</nav>

<!-- Main Content -->
<div class="content container mt-4">
    <div id="chatBotContainer" class="p-4">
        <div id="questionBox" class="mb-4"></div>
        <div id="optionsBox" class="d-flex flex-wrap gap-2"></div>
        <div id="resultBox" class="mt-4"></div>
    </div>
</div>

<script>
document.getElementById("menuIcon").addEventListener("click", () => {
    document.getElementById("sidebar").style.left = "0";
});
document.getElementById("closeSidebar").addEventListener("click", () => {
    document.getElementById("sidebar").style.left = "-400px";
});

// ChatBot Logic
const questions = [
    {
        text: "What is your fitness goal?",
        options: ["Lose weight", "Gain weight", "Maintain"]
    },
    {
        text: "How many times do you train per week?",
        options: ["0–1", "2–3", "4+"]
    },
    {
        text: "Any dietary preference?",
        options: ["Vegetarian", "Non-vegetarian", "Vegan", "No preference"]
    }
];

let answers = {};
let current = 0;

function displayQuestion() {
    if (current >= questions.length) {
        sendToGPT();
        return;
    }

    const q = questions[current];
    document.getElementById("questionBox").innerHTML = `<h5>${q.text}</h5>`;
    const optionsBox = document.getElementById("optionsBox");
    optionsBox.innerHTML = "";

    q.options.forEach(opt => {
        const btn = document.createElement("button");
        btn.textContent = opt;
        btn.className = "btn btn-outline-primary";
        btn.onclick = () => {
            if (current === 0) answers.goal = opt;
            else if (current === 1) answers.frequency = opt;
            else if (current === 2) answers.preference = opt;

            current++;
            setTimeout(displayQuestion, 300);
        };
        optionsBox.appendChild(btn);
    });
}

function sendToGPT() {
    document.getElementById("questionBox").innerHTML = "<h5>Generating your diet plan...</h5>";
    document.getElementById("optionsBox").innerHTML = "";

    fetch("diet_plan.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: new URLSearchParams(answers)
    })
    .then(res => res.json())
    .then(data => {
    const content = data.choices?.[0]?.message?.content || JSON.stringify(data, null, 2) || "No response from AI.";
    document.getElementById("resultBox").innerHTML = `
        <div class="p-3 bg-light rounded shadow">
            <h4>Your Personalized Diet Plan</h4>
            <pre style="white-space: pre-wrap;">${content}</pre>
        </div>
    `;
})

    .catch(err => {
        document.getElementById("resultBox").innerHTML = `
            <div class="alert alert-danger">Error fetching diet plan. Please try again later.</div>
        `;
        console.error("Error:", err);
    });
}

window.onload = displayQuestion;
</script>
</body>
</html>
