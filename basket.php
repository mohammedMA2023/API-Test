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

        .basket-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            border-bottom: 1px solid #ced4da;
        }

        .basket-item img {
            max-width: 80px;
            max-height: 80px;
            margin-right: 1rem;
        }

        .total {
            text-align: right;
            font-size: 1.2rem;
            margin-top: 1rem;
        }

        .checkout-btn {
            background-color: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border: none;
            cursor: pointer;
            font-size: 1rem;
        }

        .checkout-btn:hover {
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


                        <!-- Basket Section -->
                        <div class="basket-item">
                            <img src="coffee1.jpg" alt="Coffee Product">
                            <div>
                                <h3>Specialty Coffee Blend</h3>
                                <p>Quantity: 2</p>
                                <p>Price: $10.99 each</p>
                            </div>
                            <p>$21.98</p>
                        </div>

                        <div class="basket-item">
                            <img src="tea1.jpg" alt="Tea Product">
                            <div>
                                <h3>Herbal Tea Assortment</h3>
                                <p>Quantity: 1</p>
                                <p>Price: $7.99 each</p>
                            </div>
                            <p>$7.99</p>
                        </div>

                        <div class="total">
                            <p>Total: $29.97</p>
                        </div>

                        <button class="checkout-btn">Proceed to Checkout</button>

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

        <button onclick="hidePopup()">Pre-Order Now</button>
    </div>

    <footer class="footer text-faded text-center py-5">
        <div class="container">
            <p class="m-0 small">Copyright &copy; Your Website 2023</p>
        </div>
    </footer>

    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <script>
        function displayPopup(menu) {
            let popupContainer = document.getElementById('popupContainer');
            let obj = menu; // Correct variable name
            let revs = document.getElementById("foodAndDrinksSelection");
            let content = "";

            for (let i = 0; i < obj.length; i++) {
                content += `<option value="` + obj[i]["product_id"] + `">` + obj[i]["product_name"] + " (£" + obj[i]["price"] + ")" + `</option>`;
            }

            revs.innerHTML = content;
            popupContainer.style.display = 'block';
        }

        function getMenu() {
            fetch("http://192.168.0.203/api/db/getMenu")
                .then(response => response.json())
                .then(data => displayPopup(data));
        }

        function hidePopup() {
            document.getElementById('popupContainer').style.display = 'none';
        }

        var items = [];
        function addItem() {
            var selectedColor = document.getElementById("foodAndDrinksSelection");

            // Get the selected option
            var selectedOption = selectedColor.options[selectedColor.selectedIndex];

            // Get the id of the selected option
            var selectedId = selectedOption.value;
            items.push(selectedId);
            alert(JSON.stringify(items));
        }
    </script>
</body>

</html>
