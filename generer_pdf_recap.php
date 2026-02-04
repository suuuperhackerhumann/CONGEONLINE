<?php
ob_start(); // Démarrage du buffering pour éviter les erreurs "Some data has already been output"
session_start();

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

/* ======================
   CONNEXION BASE DE DONNÉES
   ====================== */
require_once __DIR__ . '/db.php';

/* ======================
   FPDF
   ====================== */
require_once __DIR__ . '/fpdf/fpdf.php';

/* ======================
   FILTRES
   ====================== */
$statut = $_GET['statut'] ?? 'tous';
$date_debut = $_GET['date_debut'] ?? null;
$date_fin = $_GET['date_fin'] ?? null;

/* ======================
   REQUÊTE
   ====================== */
$sql = "SELECT d.*, e.nom, e.prenom, t.libelle AS type_conge
        FROM demande d
        JOIN employe e ON d.id_employe = e.id
        JOIN type_demande t ON d.id_type = t.id
        WHERE 1=1";

$params = [];

if ($statut !== 'tous') {
    $sql .= " AND d.statut = ?";
    $params[] = $statut;
}
if ($date_debut) {
    $sql .= " AND d.date_debut >= ?";
    $params[] = $date_debut;
}
if ($date_fin) {
    $sql .= " AND d.date_fin <= ?";
    $params[] = $date_fin;
}

$sql .= " ORDER BY d.date_demande DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$demandes = $stmt->fetchAll(PDO::FETCH_ASSOC);

/* ======================
   STATISTIQUES
   ====================== */
$total = count($demandes);
$en_attente = count(array_filter($demandes, fn($d) => $d['statut'] === 'En attente'));
$validees = count(array_filter($demandes, fn($d) => $d['statut'] === 'Validé'));
$refusees = count(array_filter($demandes, fn($d) => $d['statut'] === 'Refusé'));

/* ======================
   PDF
   ====================== */
class PDF extends FPDF
{
    function Header()
    {
        // Logo si disponible
        if (file_exists(__DIR__ . '/../images/logo_novasys.png')) {
            $this->Image(__DIR__ . '/../images/logo_novasys.png', 10, 8, 20);
        }

        $this->SetFont('Times', 'B', 16);
        $this->SetTextColor(230, 15, 79);
        $this->Cell(0, 10, mb_convert_encoding('NOVASYS CI', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        $this->SetFont('Times', '', 11);
        $this->SetTextColor(80, 80, 80);
        $this->Cell(0, 6, mb_convert_encoding('Rapport récapitulatif des demandes de congés', 'ISO-8859-1', 'UTF-8'), 0, 1, 'C');

        
        $this->SetFont('Times', 'I', 8);
        $this->Cell(0, 5, mb_convert_encoding('Généré le ', 'ISO-8859-1', 'UTF-8'). date('d/m/Y'), 0, 1, 'C');

        $this->Ln(5);
    }

    function Footer()
    {
        $this->SetY(-15);
        $this->SetFont('Times', 'I', 8);
        $this->SetTextColor(120, 120, 120);
        $this->Cell(0, 10, 'Page ' . $this->PageNo() . '/{nb}', 0, 0, 'C');
    }
}

/* ======================
   GÉNÉRATION PDF
   ====================== */
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Times', '', 11);

/* ---- Statistiques ---- */
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(0, 8, mb_convert_encoding('Statistiques', 'ISO-8859-1', 'UTF-8'), 0, 1);
$pdf->SetFont('Times', '', 11);
$pdf->Cell(0, 6, "Total demandes : $total", 0, 1);
$pdf->Cell(0, 6, "En attente : $en_attente", 0, 1);
$pdf->Cell(0, 6, "Validees : $validees", 0, 1);
$pdf->Cell(0, 6, "Refusees : $refusees", 0, 1);

$pdf->Ln(6);

/* ---- Tableau ---- */
$pdf->SetFont('Times', 'B', 10);
$pdf->Cell(55, 8, mb_convert_encoding('Employé', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(45, 8, mb_convert_encoding('Type', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(30, 8, mb_convert_encoding('Début', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(30, 8, mb_convert_encoding('Fin', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Cell(30, 8, mb_convert_encoding('Statut', 'ISO-8859-1', 'UTF-8'), 1);
$pdf->Ln();

$pdf->SetFont('Times', '', 9);

foreach ($demandes as $d) {
    $pdf->Cell(55, 8, mb_convert_encoding($d['nom'] . ' ' . $d['prenom'], 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Cell(45, 8, mb_convert_encoding($d['type_conge'], 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Cell(30, 8, date('d/m/Y', strtotime($d['date_debut'])), 1);
    $pdf->Cell(30, 8, date('d/m/Y', strtotime($d['date_fin'])), 1);
    $pdf->Cell(30, 8, mb_convert_encoding($d['statut'], 'ISO-8859-1', 'UTF-8'), 1);
    $pdf->Ln();

    // Ajouter nouvelle page si dépassement
    if ($pdf->GetY() > 260) {
        $pdf->AddPage();
    }
}

/* ======================
   SORTIE PDF
   ====================== */
$pdf->Output('D', 'Recap_Conges_' . date('Ymd_His') . '.pdf');
exit;
?>
