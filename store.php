<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        /* Style for the menu items */
        .menu-item {
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .menu-item:hover {
            background-color: #f2f2f2;
        }

        /* Styles for the popup */
        #popupContainer {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60%;
            max-width: 400px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            text-align: center;
        }

        #popupContainer h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        #popupContainer label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
        }

        #popupContainer select,
        #popupContainer input {
            width: calc(100% - 22px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        #popupContainer button {
            width: calc(100% - 22px);
            padding: 10px;
            border-radius: 5px;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            font-weight: 400;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        #popupContainer button:hover {
            background-color: #0056b3;
        }
    </style>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Business Casual - Start Bootstrap Theme</title>
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lora:400,400i,700,700i" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body>
    <?php
    include "header.php";
    ?>
    <section class="page-section cta">
        <div class="container">
            <div class="row">
                <div class="col-xl-9 mx-auto">
                    <div class="cta-inner bg-faded text-center rounded">

                        <!-- Combined Menu -->

                        <!-- Pre-Order Button -->
                        <div class="menu-item" onclick='getMenu()'>
                            <h3>Pre-Order Food and Drinks</h3>
                            <p>Plan your visit by pre-ordering your favorites.</p>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Popup Container -->
    <div id="popupContainer">
        <h1>Pre-Order Food and Drinks</h1>

        <label for="foodAndDrinksSelection">Select Food and Drinks:</label>
        <select id="foodAndDrinksSelection" name="foodAndDrinksSelection" onchange="addItem()">
            </select>

        <label for="datepicker">Select Date:</label>
        <input type="date" id="datepicker" name="datepicker">

        <label for="timepicker">Select Time:</label>
        <input type="time" id="timepicker" name="timepicker">

        <button onclick="order()">Pre-Order Now</button>
    </div>

    <section class="page-section about-heading">
        <div class="container">
            <!-- ... (your existing about section) ... -->
        </div>
    </section>

    <footer class="footer text-faded text-center py-5">
        <div class="container">
            <p class="m-0 small">Copyright &copy; Your Website 2023</p>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <script>var items = [];

        function getMenu() {
            fetch("http://10.201.211.204/api/db/getMenu")
                .then(response => response.json())
                .then(data => displayPopup(data));
        }

        function getCookie() {
            let uId = <?php echo $_SESSION["userid"] ?>;
            return uId;
        }

        function orderItem(item) {
            alert('You selected: ' + item);
            // Add your logic for processing the order or navigating to a detailed menu page
        }

        function displayPopup(menu) {
            let popupContainer = document.getElementById('popupContainer');
            let obj = menu;
            let revs = document.getElementById("foodAndDrinksSelection");
            let content = "";
            revs.innerHTML = "";
            revs.innerHTML += `<option value="" selected></option>`;
            for (let i = 0; i < obj.length; i++) {
                content += `<option value="` + obj[i]["product_id"] + `">` + obj[i]["product_name"] + " (£" + obj[i]["price"] + ")" + `</option>`;
            }
            revs.innerHTML += content;
            popupContainer.style.display = 'block';
        }

        function hidePopup() {
            document.getElementById('popupContainer').style.display = 'none';
        }

        function addItem() {
            var selectedColor = document.getElementById("foodAndDrinksSelection");
            var selectedOption = selectedColor.options[selectedColor.selectedIndex];
            var selectedId = selectedOption.value;
            if (selectedOption.text) {
                items.push(selectedId);
            }
        }

        function order() {
            let userIdValue = getCookie();
            if (items.length > 0) {
                fetch('http://10.201.211.204/api/db/basket', {
                    method: 'POST',
                    body: JSON.stringify({ uid: userIdValue, items: items })
                });
            }
            hidePopup(); // Move this line inside the order function
        }
        </script>
</body>

</html>