<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Flight Search</title>
    <style>
    body {
        margin: 0;
        padding: 0;
        background-image: url('./img/flight.jpg');
        background-size: cover;
        background-position: center;
        height: 100vh;
        font-family: Arial, sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .form-container {
        background-color: rgba(255, 255, 255, 0.6);
        /* White color with transparency */
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.2);
        max-width: 600px;
        width: 100%;
    }

    h2 {
        text-align: center;
        color: black;
        margin-bottom: 30px;
    }

    label {
        display: block;
        font-size: 1rem;
        font-weight: bold;
        color: black;
        margin-bottom: 8px;
    }

    .form-row {
        display: flex;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        display: flex;
        flex-direction: column;
    }

    .form-row input {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 1rem;
    }

    input[type="text"]:focus,
    input[type="date"]:focus {
        outline: none;
        border-color: #888;
    }

    .submit-btn {
        width: 100%;
        padding: 12px;
        font-size: 1rem;
        font-weight: bold;
        background-color: black;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s ease;
        margin-top: 20px;
    }

    .submit-btn:hover {
        background-color: #333;
    }
    </style>
</head>

<body>
    <div class="form-container">
        <h2>Search for Flights</h2>
        <form action="process_flight_search.php" method="post">
            <!-- "From" and "To" in one row -->
            <div class="form-row">
                <div class="form-group">
                    <label for="departure">From</label>
                    <input type="text" id="departure" name="departure" placeholder="Airport Code" required>
                </div>

                <div class="form-group">
                    <label for="arrival">To</label>
                    <input type="text" id="arrival" name="arrival" placeholder="Airport Code" required>
                </div>
            </div>

            <!-- "Depart" and "Return" in one row -->
            <div class="form-row">
                <div class="form-group">
                    <label for="depart-date">Depart</label>
                    <input type="date" id="depart-date" name="depart_date" required>
                </div>

                <div class="form-group">
                    <label for="return-date">Return</label>
                    <input type="date" id="return-date" name="return_date">
                </div>
            </div>

            <button type="submit" class="submit-btn">Search Flights</button>
        </form>
    </div>

</body>

</html>