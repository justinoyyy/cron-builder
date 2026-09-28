<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Cron Expression Builder</title>

    <link rel="stylesheet" href="{{ asset('css/cron.css') }}">
</head>

<body>

    <div class="page">

        <div class="card">

            <div class="header">

                <div>
                    <h1>Cron Expression Builder</h1>

                    <p>
                        Build and preview your cron schedule
                    </p>
                </div>

                <div class="clock" id="currentTime">
                    --:--
                </div>

            </div>


            <div class="info-box">

                <strong>Cron Expression Builder</strong>

                <p>
                    Create a cron schedule visually, generate
                    the expression, and preview the next
                    scheduled runs.
                </p>

            </div>


            <div class="section">

                <h2>Visual Builder</h2>

                <div class="field-grid">


                    <div class="field">

                        <label>Minute</label>

                        <select id="minute">

                            <option value="*">
                                Every minute
                            </option>

                            <option value="0">
                                00
                            </option>

                            <option value="15">
                                15
                            </option>

                            <option value="30">
                                30
                            </option>

                            <option value="45">
                                45
                            </option>

                        </select>

                    </div>


                    <div class="field">

                        <label>Hour</label>

                        <select id="hour">

                            <option value="*">
                                Every hour
                            </option>

                            <option value="0">
                                12 AM
                            </option>

                            <option value="6">
                                6 AM
                            </option>

                            <option value="9">
                                9 AM
                            </option>

                            <option value="12">
                                12 PM
                            </option>

                            <option value="18">
                                6 PM
                            </option>

                            <option value="21">
                                9 PM
                            </option>

                        </select>

                    </div>


                    <div class="field">

                        <label>Day</label>

                        <select id="day">

                            <option value="*">
                                Every day
                            </option>

                            <option value="1">
                                1
                            </option>

                            <option value="2">
                                2
                            </option>

                            <option value="15">
                                15
                            </option>

                            <option value="30">
                                30
                            </option>

                        </select>

                    </div>


                    <div class="field">

                        <label>Month</label>

                        <select id="month">

                            <option value="*">
                                Every month
                            </option>

                            <option value="1">
                                January
                            </option>

                            <option value="2">
                                February
                            </option>

                            <option value="3">
                                March
                            </option>

                            <option value="4">
                                April
                            </option>

                            <option value="5">
                                May
                            </option>

                            <option value="6">
                                June
                            </option>

                            <option value="7">
                                July
                            </option>

                            <option value="8">
                                August
                            </option>

                            <option value="9">
                                September
                            </option>

                            <option value="10">
                                October
                            </option>

                            <option value="11">
                                November
                            </option>

                            <option value="12">
                                December
                            </option>

                        </select>

                    </div>


                    <div class="field">

                        <label>Weekday</label>

                        <select id="weekday">

                            <option value="*">
                                Every day
                            </option>

                            <option value="1-5">
                                Monday - Friday
                            </option>

                            <option value="0">
                                Sunday
                            </option>

                            <option value="1">
                                Monday
                            </option>

                            <option value="2">
                                Tuesday
                            </option>

                            <option value="3">
                                Wednesday
                            </option>

                            <option value="4">
                                Thursday
                            </option>

                            <option value="5">
                                Friday
                            </option>

                            <option value="6">
                                Saturday
                            </option>

                        </select>

                    </div>

                </div>

            </div>


            <div class="section">

                <h2>Cron Expression</h2>

                <div class="expression-box">

                    <input
                        type="text"
                        id="expression"
                        value="* * * * *"
                    >

                    <button id="copyBtn">
                        Copy
                    </button>

                </div>

                <p class="format">
                    Minute &nbsp; Hour &nbsp; Day &nbsp; Month &nbsp; Weekday
                </p>

            </div>


            <div class="section">

                <h2>Timezone</h2>

                <select id="timezone" class="timezone">

                    <option value="Asia/Manila">
                        Asia/Manila
                    </option>

                    <option value="UTC">
                        UTC
                    </option>

                    <option value="America/New_York">
                        America/New_York
                    </option>

                    <option value="America/Los_Angeles">
                        America/Los_Angeles
                    </option>

                    <option value="Europe/London">
                        Europe/London
                    </option>

                    <option value="Asia/Tokyo">
                        Asia/Tokyo
                    </option>

                </select>

            </div>


            <div class="description">

                <h2>Description</h2>

                <div id="descriptionText">
                    Every minute
                </div>

            </div>


            <div class="section">

                <div class="next-header">

                    <div>

                        <h2>
                            Next Run Times
                        </h2>

                        <p>
                            Upcoming scheduled executions
                        </p>

                    </div>

                    <button id="calculateBtn">
                        Calculate
                    </button>

                </div>


                <div id="runs" class="runs">

                    <div class="run-item">
                        Click Calculate to see next runs.
                    </div>

                </div>

            </div>


            <div class="footer">

                <button id="resetBtn" class="reset">
                    Reset
                </button>

                <button id="buildBtn" class="primary">
                    Build Schedule
                </button>

            </div>

        </div>

    </div>


    <script src="{{ asset('js/cron.js') }}"></script>

</body>

</html>