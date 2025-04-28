<?php
// Function to fetch real-time stock data from an API (mock data for demonstration)
function fetchStockData($symbol) {
    // Il API 
    $mockData = [
        'NIFTY' => [
            'price' => number_format(24000 + rand(-200, 200)), 
            'change' => rand(-200, 200),
            'percent_change' => rand(-100, 100)/100
        ],
        'BANKNIFTY' => [
            'price' => number_format(54000 + rand(-300, 300)), 
            'change' => rand(-300, 300),
            'percent_change' => rand(-150, 150)/100
        ],
        'SENSEX' => [
            'price' => number_format(79000 + rand(-600, 600)), 
            'change' => rand(-600, 600),
            'percent_change' => rand(-100, 100)/100
        ]
    ];
    
    return $mockData[$symbol] ?? ['price' => 0, 'change' => 0, 'percent_change' => 0];
}

// Fetch data for our indices
$niftyData = fetchStockData('NIFTY');
$bankniftyData = fetchStockData('BANKNIFTY');
$sensexData = fetchStockData('SENSEX');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Market Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #00d09c;
            --secondary-color: #1e2329;
            --dark-color: #161a1e;
            --light-color: #f8f9fa;
            --negative-color: #f6465d;
            --positive-color: #0ecb81;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            color: #333;
        }
        
        .dashboard-header {
            background-color: var(--primary-color);
            color: white;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .card {
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eee;
            font-weight: 600;
            padding: 0.75rem 1.25rem;
        }
        
        .index-card {
            border-left: 4px solid var(--primary-color);
        }
        
        .stock-card {
            transition: transform 0.2s;
        }
        
        .stock-card:hover {
            transform: translateY(-3px);
        }
        
        .stock-name {
            font-weight: 600;
            color: var(--secondary-color);
        }
        
        .stock-symbol {
            color: #666;
            font-size: 0.85rem;
        }
        
        .stock-price {
            font-weight: 700;
            font-size: 1.25rem;
        }
        
        .negative {
            color: var(--negative-color);
        }
        
        .positive {
            color: var(--positive-color);
        }
        
        .nav-tabs .nav-link {
            color: #666;
            font-weight: 500;
            border: none;
        }
        
        .nav-tabs .nav-link.active {
            color: var(--primary-color);
            border-bottom: 2px solid var(--primary-color);
            background-color: transparent;
        }
        
        .section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 1rem;
        }
        
        .divider {
            border-top: 1px solid #eee;
            margin: 1.5rem 0;
        }
        
        .modal-content {
            border-radius: 10px;
        }
        
        .calculator-form .form-group {
            margin-bottom: 1rem;
        }
        
        #calculatorResult {
            background-color: #f8f9fa;
            padding: 1rem;
            border-radius: 5px;
            margin-top: 1rem;
            display: none;
        }
        
        .blink {
            animation: blink-animation 1s steps(5, start) infinite;
        }
        
        @keyframes blink-animation {
            to { opacity: 0.5; }
        }
        
        .refresh-btn {
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .refresh-btn:hover {
            transform: rotate(180deg);
        }
    </style>
</head>
<body>
    <div class="dashboard-header">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="mb-0"><i class="fas fa-chart-line me-2"></i> Stock Dashboard</h1>
                <div class="text-white">
                    <span id="currentTime" class="me-3"></span>
                    <span id="currentDate"></span>
                    <button class="logout-btn" onclick="window.location.href='logout.php'">Logout</button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="container">
        <!-- Navigation Tabs -->
        <ul class="nav nav-tabs mb-4">
            <li class="nav-item">
                <a class="nav-link active" href="#" id="overviewTab"><i class="fas fa-home me-1"></i> Overview</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="foTab"><i class="fas fa-exchange-alt me-1"></i> F&O</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#" id="mfTab"><i class="fas fa-piggy-bank me-1"></i> Mutual Funds</a>
            </li>
        </ul>

        
        
        <!-- Indices Section -->
        <div class="row">
            <div class="col-md-4">
                <div class="card index-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="stock-symbol">NIFTY</div>
                            <i class="fas fa-sync-alt refresh-btn" onclick="refreshData('NIFTY')"></i>
                        </div>
                        <div class="stock-price" id="niftyPrice"><?= $niftyData['price'] ?></div>
                        <div class="<?= ($niftyData['change'] < 0) ? 'negative' : 'positive' ?>" id="niftyChange">
                            <?= ($niftyData['change'] < 0 ? '' : '+') . $niftyData['change'] ?> 
                            (<?= ($niftyData['percent_change'] < 0 ? '' : '+') . number_format($niftyData['percent_change'], 2) ?>%) 
                            <i class="fas fa-arrow-<?= ($niftyData['change'] < 0 ? 'down' : 'up') ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card index-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="stock-symbol">SENSEX</div>
                            <i class="fas fa-sync-alt refresh-btn" onclick="refreshData('SENSEX')"></i>
                        </div>
                        <div class="stock-price" id="sensexPrice"><?= $sensexData['price'] ?></div>
                        <div class="<?= ($sensexData['change'] < 0) ? 'negative' : 'positive' ?>" id="sensexChange">
                            <?= ($sensexData['change'] < 0 ? '' : '+') . $sensexData['change'] ?> 
                            (<?= ($sensexData['percent_change'] < 0 ? '' : '+') . number_format($sensexData['percent_change'], 2) ?>%) 
                            <i class="fas fa-arrow-<?= ($sensexData['change'] < 0 ? 'down' : 'up') ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4">
                <div class="card index-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div class="stock-symbol">BANKNIFTY</div>
                            <i class="fas fa-sync-alt refresh-btn" onclick="refreshData('BANKNIFTY')"></i>
                        </div>
                        <div class="stock-price" id="bankniftyPrice"><?= $bankniftyData['price'] ?></div>
                        <div class="<?= ($bankniftyData['change'] < 0) ? 'negative' : 'positive' ?>" id="bankniftyChange">
                            <?= ($bankniftyData['change'] < 0 ? '' : '+') . $bankniftyData['change'] ?> 
                            (<?= ($bankniftyData['percent_change'] < 0 ? '' : '+') . number_format($bankniftyData['percent_change'], 2) ?>%) 
                            <i class="fas fa-arrow-<?= ($bankniftyData['change'] < 0 ? 'down' : 'up') ?>"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Most Traded Section -->
        <h5 class="section-title"><i class="fas fa-fire me-2"></i> My Recommendation</h5>
        
        <div class="row">
            <div class="col-md-3">
                <div class="card stock-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stock-symbol">WAAFEFF</div>
                                <div class="stock-name">Waaree Energies</div>
                            </div>
                            <i class="fas fa-star text-warning"></i>
                        </div>
                        <div class="stock-price mt-2">₹2,676.50</div>
                        <div class="negative">-163.40 (5.75%) <i class="fas fa-arrow-down"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stock-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stock-symbol">VI</div>
                                <div class="stock-name">Vodafone Idea</div>
                            </div>
                            <i class="far fa-star text-muted"></i>
                        </div>
                        <div class="stock-price mt-2">₹7.47</div>
                        <div class="negative">-0.46 (5.80%) <i class="fas fa-arrow-down"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stock-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stock-symbol">IEX</div>
                                <div class="stock-name">Indian Energy Exchange</div>
                            </div>
                            <i class="far fa-star text-muted"></i>
                        </div>
                        <div class="stock-price mt-2">₹190.50</div>
                        <div class="negative">-0.34 (0.18%) <i class="fas fa-arrow-down"></i></div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card stock-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="stock-symbol">BSE</div>
                                <div class="stock-name">Bombay Stock Exchange</div>
                            </div>
                            <i class="far fa-star text-muted"></i>
                        </div>
                        <div class="stock-price mt-2">₹6,303.50</div>
                        <div class="negative">-190.00 (2.93%) <i class="fas fa-arrow-down"></i></div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="divider"></div>
        
        <!-- Products & Tools Section -->
        <h5 class="section-title"><i class="fas fa-tools me-2"></i> Products & Tools</h5>
        
        <div class="row">
            <div class="col-md-3">
                <div class="card clickable-card" data-bs-toggle="modal" data-bs-target="#marketAnalysisModal">
                    <div class="card-body text-center">
                        <i class="fas fa-chart-pie text-primary mb-2" style="font-size: 2rem;"></i>
                        <h6>Market Analysis</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card clickable-card" data-bs-toggle="modal" data-bs-target="#priceAlertsModal">
                    <div class="card-body text-center">
                        <i class="fas fa-bell text-primary mb-2" style="font-size: 2rem;"></i>
                        <h6>Price Alerts</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card clickable-card" data-bs-toggle="modal" data-bs-target="#marketNewsModal">
                    <div class="card-body text-center">
                        <i class="fas fa-newspaper text-primary mb-2" style="font-size: 2rem;"></i>
                        <h6>Market News</h6>
                    </div>
                </div>
            </div>
            
            <div class="col-md-3">
                <div class="card clickable-card" data-bs-toggle="modal" data-bs-target="#calculatorModal">
                    <div class="card-body text-center">
                        <i class="fas fa-calculator text-primary mb-2" style="font-size: 2rem;"></i>
                        <h6>Returns Calculator</h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Market Analysis Modal -->
    <div class="modal fade" id="marketAnalysisModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-chart-pie me-2"></i> Market Analysis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Sector Performance</h6>
                            <canvas id="sectorChart" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h6>Market Sentiment</h6>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-success" role="progressbar" style="width: 65%">Bullish 65%</div>
                            </div>
                            <div class="progress mb-2">
                                <div class="progress-bar bg-warning" role="progressbar" style="width: 25%">Neutral 25%</div>
                            </div>
                            <div class="progress">
                                <div class="progress-bar bg-danger" role="progressbar" style="width: 10%">Bearish 10%</div>
                            </div>
                            
                            <h6 class="mt-4">Top Gainers/Losers</h6>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Stock</th>
                                        <th>Change</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>RELIANCE</td>
                                        <td class="positive">+2.5%</td>
                                    </tr>
                                    <tr>
                                        <td>HDFCBANK</td>
                                        <td class="negative">-1.8%</td>
                                    </tr>
                                    <tr>
                                        <td>TCS</td>
                                        <td class="positive">+1.2%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save Analysis</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Price Alerts Modal -->
    <div class="modal fade" id="priceAlertsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-bell me-2"></i> Price Alerts</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="alertForm">
                        <div class="mb-3">
                            <label class="form-label">Stock Symbol</label>
                            <input type="text" class="form-control" placeholder="e.g. RELIANCE">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alert Condition</label>
                            <select class="form-select">
                                <option>Price rises above</option>
                                <option>Price falls below</option>
                                <option>Percentage change up</option>
                                <option>Percentage change down</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Value</label>
                            <input type="number" class="form-control" placeholder="Enter value">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Set Alert</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Market News Modal -->
    <div class="modal fade" id="marketNewsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-newspaper me-2"></i> Market News</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="list-group">
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">Nifty hits record high amid global rally</h6>
                                <small>2 hours ago</small>
                            </div>
                            <p class="mb-1">The Nifty 50 index surged to a new all-time high of 24,200 points today...</p>
                            <small>Economic Times</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">RBI keeps repo rate unchanged at 6.5%</h6>
                                <small>5 hours ago</small>
                            </div>
                            <p class="mb-1">The Reserve Bank of India maintained the status quo on policy rates...</p>
                            <small>Business Standard</small>
                        </a>
                        <a href="#" class="list-group-item list-group-item-action">
                            <div class="d-flex w-100 justify-content-between">
                                <h6 class="mb-1">IT sector shows signs of recovery in Q1 earnings</h6>
                                <small>Yesterday</small>
                            </div>
                            <p class="mb-1">Major IT firms reported better-than-expected earnings for the first quarter...</p>
                            <small>Moneycontrol</small>
                        </a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Calculator Modal -->
    <div class="modal fade" id="calculatorModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-calculator me-2"></i> Investment Returns Calculator</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="calculatorForm" class="calculator-form">
                        <div class="form-group">
                            <label for="initialInvestment">Initial Investment (₹)</label>
                            <input type="number" class="form-control" id="initialInvestment" value="10000" required>
                        </div>
                        <div class="form-group">
                            <label for="monthlyInvestment">Monthly Investment (₹)</label>
                            <input type="number" class="form-control" id="monthlyInvestment" value="1000">
                        </div>
                        <div class="form-group">
                            <label for="investmentPeriod">Investment Period (years)</label>
                            <input type="number" class="form-control" id="investmentPeriod" value="5" required>
                        </div>
                        <div class="form-group">
                            <label for="expectedReturn">Expected Annual Return (%)</label>
                            <input type="number" step="0.1" class="form-control" id="expectedReturn" value="12" required>
                        </div>
                        <button type="button" class="btn btn-primary w-100 mt-3" onclick="calculateReturns()">
                            Calculate Returns
                        </button>
                    </form>
                    <div id="calculatorResult">
                        <h6>Projected Returns</h6>
                        <table class="table table-sm">
                            <tr>
                                <td>Total Invested</td>
                                <td id="totalInvested" class="text-end">₹0</td>
                            </tr>
                            <tr>
                                <td>Estimated Returns</td>
                                <td id="estimatedReturns" class="text-end">₹0</td>
                            </tr>
                            <tr class="table-success">
                                <td>Total Value</td>
                                <td id="totalValue" class="text-end fw-bold">₹0</td>
                            </tr>
                        </table>
                        <canvas id="returnsChart" height="150"></canvas>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Update current time and date
        function updateDateTime() {
            const now = new Date();
            document.getElementById('currentTime').textContent = now.toLocaleTimeString();
            document.getElementById('currentDate').textContent = now.toLocaleDateString('en-US', { 
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' 
            });
        }
        
        setInterval(updateDateTime, 1000);
        updateDateTime();
        
        // Make cards clickable
        document.querySelectorAll('.clickable-card').forEach(card => {
            card.style.cursor = 'pointer';
        });
        
        // Tab functionality
        document.getElementById('overviewTab').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Overview tab clicked - in a real app this would load overview content');
        });
        
        document.getElementById('foTab').addEventListener('click', function(e) {
            e.preventDefault();
            alert('F&O tab clicked - in a real app this would load futures & options content');
        });
        
        document.getElementById('mfTab').addEventListener('click', function(e) {
            e.preventDefault();
            alert('Mutual Funds tab clicked - in a real app this would load mutual funds content');
        });
        
        // Refresh stock data
        function refreshData(symbol) {
            const priceElement = document.getElementById(symbol.toLowerCase() + 'Price');
            const changeElement = document.getElementById(symbol.toLowerCase() + 'Change');
            
            // Show loading state
            priceElement.classList.add('blink');
            changeElement.classList.add('blink');
            
            // Simulate API call with timeout
            setTimeout(() => {
                // In a real app, you would make an AJAX call to your PHP backend
                // which would fetch real-time data from a financial API
                
                // Mock data update
                const mockChange = (Math.random() - 0.5) * 400;
                const mockPrice = {
                    'nifty': 24000 + (Math.random() - 0.5) * 200,
                    'banknifty': 54000 + (Math.random() - 0.5) * 300,
                    'sensex': 79000 + (Math.random() - 0.5) * 600
                }[symbol.toLowerCase()];
                
                const mockPercentChange = (mockChange / mockPrice * 100).toFixed(2);
                
                priceElement.textContent = mockPrice.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                
                if (mockChange < 0) {
                    changeElement.className = 'negative';
                    changeElement.innerHTML = `${mockChange.toFixed(2)} (${mockPercentChange}%) <i class="fas fa-arrow-down"></i>`;
                } else {
                    changeElement.className = 'positive';
                    changeElement.innerHTML = `+${mockChange.toFixed(2)} (+${mockPercentChange}%) <i class="fas fa-arrow-up"></i>`;
                }
                
                // Remove loading state
                priceElement.classList.remove('blink');
                changeElement.classList.remove('blink');
            }, 1000);
        }
        
        // Initialize sector chart in market analysis modal
        function initSectorChart() {
            const ctx = document.getElementById('sectorChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['IT', 'Banking', 'Auto', 'Pharma', 'FMCG', 'Energy'],
                    datasets: [{
                        data: [25, 20, 15, 12, 10, 18],
                        backgroundColor: [
                            '#FF6384',
                            '#36A2EB',
                            '#FFCE56',
                            '#4BC0C0',
                            '#9966FF',
                            '#FF9F40'
                        ]
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'right',
                        }
                    }
                }
            });
        }
        
        // Calculate investment returns
        function calculateReturns() {
            const initial = parseFloat(document.getElementById('initialInvestment').value) || 0;
            const monthly = parseFloat(document.getElementById('monthlyInvestment').value) || 0;
            const years = parseFloat(document.getElementById('investmentPeriod').value) || 1;
            const annualReturn = parseFloat(document.getElementById('expectedReturn').value) || 0;
            
            const months = years * 12;
            const monthlyRate = annualReturn / 100 / 12;
            
            // Calculate future value of initial investment
            const fvInitial = initial * Math.pow(1 + monthlyRate, months);
            
            // Calculate future value of monthly investments
            const fvMonthly = monthly * ((Math.pow(1 + monthlyRate, months) - 1) / monthlyRate);
            
            const totalInvested = initial + (monthly * months);
            const totalValue = fvInitial + fvMonthly;
            const estimatedReturns = totalValue - totalInvested;
            
            // Display results
            document.getElementById('totalInvested').textContent = '₹' + totalInvested.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById('estimatedReturns').textContent = '₹' + estimatedReturns.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            document.getElementById('totalValue').textContent = '₹' + totalValue.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ",");
            
            // Show results
            document.getElementById('calculatorResult').style.display = 'block';
            
            // Draw chart
            drawReturnsChart(totalInvested, estimatedReturns);
        }
        
        // Draw returns chart
        function drawReturnsChart(invested, returns) {
            const ctx = document.getElementById('returnsChart').getContext('2d');
            
            // Destroy previous chart if it exists
            if (window.returnsChart) {
                window.returnsChart.destroy();
            }
            
            window.returnsChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Amount Invested', 'Estimated Returns'],
                    datasets: [{
                        data: [invested, returns],
                        backgroundColor: [
                            '#36A2EB',
                            '#4BC0C0'
                        ],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    cutout: '70%',
                    plugins: {
                        legend: {
                            position: 'bottom',
                        }
                    }
                }
            });
        }
        
        // Initialize modals
        const marketAnalysisModal = new bootstrap.Modal(document.getElementById('marketAnalysisModal'));
        marketAnalysisModal._element.addEventListener('shown.bs.modal', initSectorChart);
        
        const calculatorModal = new bootstrap.Modal(document.getElementById('calculatorModal'));
        calculatorModal._element.addEventListener('shown.bs.modal', function() {
            // Reset calculator on show
            document.getElementById('calculatorResult').style.display = 'none';
        });
    </script>
</body>
</html>