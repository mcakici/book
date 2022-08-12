<?php
class trainApp
{
    private array $trainData;
    private int $numberOfPeople = 0;
    private int $waitQueue = 0;
    private bool $differentVagons = false;
    private bool $reservationCanBeMade = false;
    private array $availableCarts, $seatPlanDetails = [];

    public function __construct()
    {
        if ($_SERVER['REQUEST_METHOD'] !== "POST") return false;
        if (!isset($_POST["Tren"])){
            $request_body = file_get_contents('php://input');
            $_POST = json_decode($request_body, true);
        }
        if (!isset($_POST["Tren"])) return false;

        $this->trainData = $_POST["Tren"];
        $this->numberOfPeople = intval($_POST["RezervasyonYapilacakKisiSayisi"]);
        if($this->numberOfPeople < 1) return $this->showResponse(); 
        $this->differentVagons = $_POST["KisilerFarkliVagonlaraYerlestirilebilir"] ? true : false;
        $this->waitQueue = $this->numberOfPeople;

        $this->calculateEmptySeats();
        $this->bookSeats();
        $this->showResponse();
    }

    public function __destruct()
    {
    }

    private function calculateEmptySeats()
    {
        if (isset($this->trainData["Vagonlar"]) && count($this->trainData["Vagonlar"]) > 0) {
            foreach ($this->trainData["Vagonlar"] as $key => $cart) {
                if ($cart["Kapasite"] * 0.7 > $cart["DoluKoltukAdet"]) { // Do not allow booking over %70 for online
                    $this->availableCarts[] = ["index" => $key, "empty" => intval(($cart["Kapasite"] * 0.7) - $cart["DoluKoltukAdet"])];
                }
            }
        }
    }

    private function bookSeats()
    {
        if ($this->differentVagons === false) {
            foreach ($this->availableCarts as $key => $ac) {
                if ($ac["empty"] >= $this->numberOfPeople) {
                    $this->seatPlanDetails[] = ["VagonAdi" => $this->trainData["Vagonlar"][$ac["index"]]["Ad"], "KisiSayisi" => $this->numberOfPeople];
                    $this->reservationCanBeMade = true;
                    break;
                }
            }
        } else {
            foreach ($this->availableCarts as $key => $ac) {
                $this->seatPlanDetails[] = ["VagonAdi" => $this->trainData["Vagonlar"][$ac["index"]]["Ad"], "KisiSayisi" => ($ac["empty"] >= $this->waitQueue ? $this->waitQueue : $ac["empty"])];
                $this->waitQueue -= ($ac["empty"] >= $this->waitQueue ? $this->waitQueue : $ac["empty"]);

                if ($this->waitQueue <= 0) {
                    $this->reservationCanBeMade = true;
                    break;
                }
            }
        }
    }

    public function showResponse()
    {
        header('Content-type: application/json');
        echo json_encode(["RezervasyonYapilabilir" => $this->reservationCanBeMade, "YerlesimAyrinti" => ($this->reservationCanBeMade ? $this->seatPlanDetails : [])]);
    }
}
