<?php
include('db.php');

// GRAFIKON DOGAĐAJA PO LOKACIJAMA
$getVenueDataQuery = "SELECT location, COUNT(*) AS count FROM venue GROUP BY location";
$venueDataResult = mysqli_query($conn, $getVenueDataQuery);

$locations = [];
$counts = [];

while ($row = mysqli_fetch_assoc($venueDataResult)) {
    $locations[] = $row['location'];
    $counts[] = $row['count'];
}

// GRAFIKON KUPLJENIH KARATA I NJENIH CIJENA
$getTotalPriceQuery = "SELECT resNumber, SUM(price) AS totalPrice FROM reservation GROUP BY resNumber ORDER BY resID ASC";
$totalPriceResult = mysqli_query($conn, $getTotalPriceQuery);

$resNumbers = [];
$totalPrice = [];

// Pretvaranje rezultata u odgovarajući format
if ($totalPriceResult && mysqli_num_rows($totalPriceResult) > 0) {
    while ($row = mysqli_fetch_assoc($totalPriceResult)) {
        $resNumbers[] = $row['resNumber'];
        $totalPrice[] = $row['totalPrice'];
    }
}

// DNEVNE ZARADE
$currentDate = date("Y-m-d");
$getTodaysEarningsQuery = "SELECT SUM(price) AS todaysEarnings FROM reservation WHERE DATE(date) = '$currentDate'";
$todaysEarningsResult = mysqli_query($conn, $getTodaysEarningsQuery);

$todaysEarnings = 0;

// Pretvaranje rezultata u odgovarajući format
if ($todaysEarningsResult && mysqli_num_rows($todaysEarningsResult) > 0) {
    $row = mysqli_fetch_assoc($todaysEarningsResult);
    $todaysEarnings = $row['todaysEarnings'] ?? 0; // Postavljanje na nulu ako je null
} else {
    $todaysEarnings = 0;
}

// MJESECNE ZARADE
$currentMonth = date('m');
$currentYear = date('Y');

$getMonthlyEarningsQuery = "SELECT SUM(price) AS monthlyEarnings FROM reservation WHERE MONTH(date) = ? AND YEAR(date) = ?";
$stmtMonthlyEarnings = $conn->prepare($getMonthlyEarningsQuery);
$stmtMonthlyEarnings->bind_param("ii", $currentMonth, $currentYear);
$stmtMonthlyEarnings->execute();
$resultMonthlyEarnings = $stmtMonthlyEarnings->get_result();
$rowMonthlyEarnings = $resultMonthlyEarnings->fetch_assoc();
$monthlyEarnings = $rowMonthlyEarnings['monthlyEarnings'] ?? 0; // Postavljanje na nulu ako je null

// DNEVNI KORISNICI
$currentDate = date('Y-m-d');

$getDailyUsersQuery = "SELECT COUNT(*) AS dailyUsers FROM user WHERE DATE(date) = ?";
$stmtDailyUsers = $conn->prepare($getDailyUsersQuery);
$stmtDailyUsers->bind_param("s", $currentDate);
$stmtDailyUsers->execute();
$resultDailyUsers = $stmtDailyUsers->get_result();
$rowDailyUsers = $resultDailyUsers->fetch_assoc();
$dailyUsers = $rowDailyUsers['dailyUsers'] ?? 0; // Postavljanje na nulu ako je null

// MJESECNI KORISNICI
$currentMonth = date('Y-m');

$getMonthlyUsersQuery = "SELECT COUNT(*) AS monthlyUsers FROM user WHERE DATE_FORMAT(date, '%Y-%m') = ?";
$stmtMonthlyUsers = $conn->prepare($getMonthlyUsersQuery);
$stmtMonthlyUsers->bind_param("s", $currentMonth);
$stmtMonthlyUsers->execute();
$resultMonthlyUsers = $stmtMonthlyUsers->get_result();
$rowMonthlyUsers = $resultMonthlyUsers->fetch_assoc();
$monthlyUsers = $rowMonthlyUsers['monthlyUsers'] ?? 0; // Postavljanje na nulu ako je null

?>
<link rel="stylesheet" href="css/admin.css" />
<div class="w-100 overflow-hidden position-relative text-white" data-aos="fade">
    <div class="container-fluid py-vh-4 position-relative mt-5 px-vw-5 text-center">
        <div class="row align-items-center justify-content-center py-vh-5">

            <div class="container text-center" style="width:100%;max-width:1250px">
                <div class="row row-cols-4 ">
                    <div class="col j">
                        <p>TODAY'S EARNINGS</p>
                        <h5>
                            <i class="bi bi-caret-up-fill" style="font-size: large; color: green; margin:auto; padding:3px 10px 10px 3px"></i>
                            <?php echo $todaysEarnings; ?> €
                        </h5>
                    </div>
                    <div class="col j">
                        <p>TODAY'S USERS</p>
                        <h5>
                            <i class="bi bi-caret-up-fill" style="font-size: large; color: green; margin:auto; padding:3px 10px 10px 3px"></i>
                            <?php echo $dailyUsers; ?>
                        </h5>
                    </div>
                    <div class="col j">
                        <p>MONTHLY EARNINGS</p>
                        <h5>
                            <i class="bi bi-caret-up-fill" style="font-size: large; color: green; margin:auto; padding:3px 10px 10px 3px"></i>
                            <?php echo $monthlyEarnings; ?> €
                        </h5>
                    </div>
                    <div class="col j">
                        <p>MONTHLY USERS</p>
                        <h5>
                            <i class="bi bi-caret-down-fill" style="font-size: large; color: #9a1818; margin:auto; padding:3px 10px 10px 3px"></i>
                            <?php echo $monthlyUsers; ?>
                        </h5>
                    </div>
                </div>
            </div>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js"></script>

            <div class="row">
                <div class="col-8 poz1">
                    <h5>TICKET SALES</h5>
                    <canvas id="myChart2" style="width:100%;max-width:600px"></canvas>
                    <script>
                        const x2Values = <?php echo json_encode($resNumbers); ?>;
                        const y2Values = <?php echo json_encode($totalPrice); ?>;

                        new Chart("myChart2", {
                            type: "line",
                            data: {
                                labels: x2Values,
                                datasets: [{
                                    fill: false,
                                    lineTension: 0.5,
                                    backgroundColor: "rgba(0,0,255,1.0)",
                                    borderColor: "rgba(0,0,255,0.1)",
                                    data: y2Values
                                }]
                            },
                            options: {
                                legend: {
                                    display: false,
                                },
                                scales: {
                                    yAxes: [{
                                        ticks: {
                                            beginAtZero: true // Postavljanje da grafikon počinje od nule
                                        },
                                        scaleLabel: {
                                            display: true,
                                            labelString: 'Ukupna cijena' // Postavljanje oznake za y osu
                                        }
                                    }],
                                }
                            }
                        });
                    </script>
                </div>

                <div class="col-4 poz2">
                    <h5>POPULAR EVENT CITIES </h5>
                    <canvas id="myChart" style="width:100%;max-width:600px"></canvas>
                    <script>
                        const xValues = <?php echo json_encode($locations); ?>;
                        const yValues = <?php echo json_encode($counts); ?>;
                        const barColors = [
                            "#9e0142",
                            "#d53e4f",
                            "#f46d43",
                            "#fdae61",
                            "#fee08b",
                            "#e6f598",
                            "#abdda4",
                            "#66c2a5",
                            "#3288bd",
                            "#5e4fa2",
                            "#9e0142",
                            "#d53e4f",
                            "#f46d43",
                            "#fdae61",
                            "#fee08b",
                            "#e6f598",
                            "#abdda4",
                            "#66c2a5",
                            "#3288bd",
                            "#5e4fa2",
                            "#9e0142",
                            "#d53e4f",
                            "#f46d43",
                            "#fdae61",
                            "#fee08b",
                            "#e6f598",
                            "#abdda4",
                            "#66c2a5",
                            "#3288bd",
                            "#5e4fa2",
                        ];

                        new Chart("myChart", {
                            type: "pie",
                            data: {
                                labels: xValues,
                                datasets: [{
                                    backgroundColor: barColors,
                                    data: yValues
                                }]
                            },
                            options: {
                                title: {
                                    // display: true,
                                    // text: "Gradovi koji organizuju događaje"
                                },
                                aspectRatio: 1.4, // Postavljanje omjera širine i visine na 1 (krug)
                                plugins: {
                                    legend: {
                                        position: 'bottom'
                                    }
                                }
                            }
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
</div>