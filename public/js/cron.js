const minute = document.getElementById("minute");
const hour = document.getElementById("hour");
const day = document.getElementById("day");
const month = document.getElementById("month");
const weekday = document.getElementById("weekday");

const expression = document.getElementById("expression");
const timezone = document.getElementById("timezone");

const descriptionText = document.getElementById("descriptionText");
const runs = document.getElementById("runs");

const calculateBtn = document.getElementById("calculateBtn");
const buildBtn = document.getElementById("buildBtn");
const resetBtn = document.getElementById("resetBtn");
const copyBtn = document.getElementById("copyBtn");

const currentTime = document.getElementById("currentTime");


function updateExpression() {

    expression.value =
        `${minute.value} ${hour.value} ${day.value} ${month.value} ${weekday.value}`;

    updateDescription();
}


function updateDescription() {

    const value = expression.value.trim();

    if (value === "* * * * *") {
        descriptionText.textContent = "Every minute";
        return;
    }

    if (value === "0 * * * *") {
        descriptionText.textContent = "Every hour";
        return;
    }

    if (value === "0 0 * * *") {
        descriptionText.textContent = "Every day at midnight";
        return;
    }

    if (value === "0 12 * * *") {
        descriptionText.textContent = "Every day at 12:00 PM";
        return;
    }

    if (value === "0 9 * * 1-5") {
        descriptionText.textContent =
            "Every weekday at 9:00 AM";
        return;
    }

    if (value === "0 0 1 * *") {
        descriptionText.textContent =
            "At midnight on the first day of every month";
        return;
    }

    descriptionText.textContent =
        "Runs according to the selected cron schedule.";
}


function updateClock() {

    const now = new Date();

    currentTime.textContent =
        now.toLocaleTimeString([], {
            hour: "2-digit",
            minute: "2-digit"
        });
}


async function calculateCron() {

    const value = expression.value.trim();

    runs.innerHTML = `
        <div class="run-item">
            Calculating next run times...
        </div>
    `;

    try {

        const response = await fetch("/calculate-cron", {

            method: "POST",

            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .content
            },

            body: JSON.stringify({
                expression: value,
                timezone: timezone.value
            })
        });

        const data = await response.json();

        if (!data.success) {

            runs.innerHTML = `
                <div class="run-item error">
                    ${data.message}
                </div>
            `;

            return;
        }

        descriptionText.textContent =
            data.description;

        runs.innerHTML = "";

        data.nextRuns.forEach(function(run) {

            const item = document.createElement("div");

            item.className = "run-item";

            item.textContent = run;

            runs.appendChild(item);
        });

    } catch (error) {

        runs.innerHTML = `
            <div class="run-item error">
                Unable to calculate the schedule.
            </div>
        `;
    }
}


function resetBuilder() {

    minute.value = "*";
    hour.value = "*";
    day.value = "*";
    month.value = "*";
    weekday.value = "*";

    expression.value = "* * * * *";

    timezone.value = "Asia/Manila";

    descriptionText.textContent = "Every minute";

    runs.innerHTML = `
        <div class="run-item">
            Click Calculate to see next runs.
        </div>
    `;
}


async function copyExpression() {

    await navigator.clipboard.writeText(
        expression.value
    );

    copyBtn.textContent = "Copied!";

    setTimeout(function() {
        copyBtn.textContent = "Copy";
    }, 1500);
}


minute.addEventListener("change", updateExpression);
hour.addEventListener("change", updateExpression);
day.addEventListener("change", updateExpression);
month.addEventListener("change", updateExpression);
weekday.addEventListener("change", updateExpression);

expression.addEventListener("input", updateDescription);

calculateBtn.addEventListener("click", calculateCron);

buildBtn.addEventListener("click", calculateCron);

resetBtn.addEventListener("click", resetBuilder);

copyBtn.addEventListener("click", copyExpression);


setInterval(updateClock, 1000);

updateClock();
updateDescription();