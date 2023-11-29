<?php
// Pega a variável da página 'processo.php' e é adicionada aqui
$saldoAtual = $_POST['saldoAtual'];

// Conecte-se ao banco de dados e obtenha os gastos do usuário
$conexao = new mysqli('localhost', 'root', '', 'lancamentos');
$sql = "SELECT DAY(dia) as dia, SUM(valor) as total FROM tabelas WHERE MONTH(dia) = MONTH(CURRENT_DATE()) AND YEAR(dia) = YEAR(CURRENT_DATE()) GROUP BY dia";
$resultado = $conexao->query($sql);

// Prepare os dados para o gráfico
$dados_dias = array();
$dados_gastos = array();
while ($linha = $resultado->fetch_assoc()) {
    $dados_dias[] = 'Dia: ' . $linha['dia'];
    $dados_gastos[] = (float)$linha['total'];
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="CSS/resul-style.css">
    <title>Resultado</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container-fluid text-center">
        <h1>CompassOne</h1>
    </div>

    <!-- Processo de adição de gráfico sobre os gastos do usuário -->
    <canvas id="myChart" width="350" height="100"></canvas>
    <script>
    var ctx = document.getElementById('myChart');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: [<?php echo "'" . implode("', '", $dados_dias) . "'"; ?>],
            datasets: [{
                label: 'Gastos do Usuário',
                data: [<?php echo implode(", ", $dados_gastos); ?>],
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    </script>

    <h2>
        <!-- Mostra o saldo restante do usuário -->
        Seu Saldo atual é de <?php echo $saldoAtual?>
    </h2>

    <?php
    if ($saldoAtual < 100) {
        echo '<script>alert("Seu saldo está ficando baixo. Por favor, considere reduzir seus gastos.");</script>';
    }
    ?>

    <!-- Retorna à página de lançamento de dados -->
    <button onclick="location.href='index.html'">Voltar para o inicio</button>
</body>
</html>
