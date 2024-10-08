<!DOCTYPE html>
<html lang="en" data-theme="light">

<body>
</body>

</html>

<?php
# Завдання: Розробка системи оренди транспортних засобів з використанням ООП у PHP

# Мета: Створити систему оренди транспортних засобів, що демонструє ключові принципи ООП у PHP.

## Вимоги до реалізації:

// - Використовуйте правильні модифікатори доступу (public, protected, private)
// - Застосовуйте типізацію параметрів та значень, що повертаються, де це можливо
// - Дотримуйтесь принципів ООП: успадкування, поліморфізм, інкапсуляцію
// - Додайте коментарі до методів та класів для покращення читабельності коду

## Додаткові завдання (за бажанням):

// - Реалізуйте обробку помилок з використанням винятків (exceptions)
// - Додайте додаткові методи до класу `RentalSystem` для керування списком транспортних засобів
// - Створіть додаткові класи платіжних методів, що реалізують інтерфейс `PaymentMethod`


## Частина 1: Створення базової структури

/* 1. Створіть інтерфейс `PaymentMethod` з методом `processPayment($amount)`.

   2. Розробіть абстрактний клас `Vehicle` з наступними характеристиками:
   - Захищені властивості: `$brand`, `$model`, `$year`
   - Конструктор, що приймає ці три параметри
   - Абстрактний метод `calculateRentalCost($days)`
   - Метод `getInfo()`, що повертає рядок з інформацією про транспортний засіб */

# Створення інтерфейсу PaymentMethod
interface PaymentMethod
{
    # Створення методу processPayment($amount)
    public function processPayment($amount);
}

# Створення абстрактного класу `Vehicle`
abstract class Vehicle
{
    protected $brand, $model, $year;

    public function __construct($brand, $model, $year)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->year = $year;
    }

    # Створення абстрактної функції для підрахунку оренди транспортного засобу
    abstract public function calculateRentalCost($days);

    # Створення методу, що повертає рядок з інформацією про транспортний засіб
    public function getInfo()
    {
        return "brand: $this->brand, model: $this->model, year: $this->year /";
    }
}

## Частина 2: Реалізація конкретних класів

/* 3. Створіть клас `Car`, що успадковує від `Vehicle`:
   - Додайте приватну властивість `$dailyRate`
   - Реалізуйте конструктор та метод `calculateRentalCost()` 

 4. Аналогічно створіть клас `Motorcycle`:
   - Додайте приватну властивість `$hourlyRate`
   - Реалізуйте конструктор та метод `calculateRentalCost()` 

 5. Розробіть клас `CreditCardPayment`, що реалізує інтерфейс `PaymentMethod`:
   - Додайте приватні властивості `$cardNumber` та `$expirationDate`
   - Реалізуйте конструктор та метод `processPayment()` */

# Створення класу Car, що є дочірнім класом абстрактного класу Vehicle 
class Car extends Vehicle
{
    private $dailyRate;

    public function __construct($brand, $model, $year, $dailyRate)
    {
        parent::__construct($brand, $model, $year);
        $this->dailyRate = $dailyRate;
    }

    # Створення методу, що повертає ціну оренди за певну кількість днів
    public function calculateRentalCost($days)
    {
        return $this->dailyRate * $days;
    }

    # Створення методу, що повертає тип транспортного засобу 
    public function getType()
    {
        return "car";
    }
}

# Створення класу Motorcycle, що є дочірнім класом абстрактного класу Vehicle 
class Motorcycle extends Vehicle
{
    private $hourlyRate;

    public function __construct($brand, $model, $year, $hourlyRate)
    {
        parent::__construct($brand, $model, $year);
        $this->hourlyRate = $hourlyRate;
    }

    # Створення методу, що повертає ціну оренди за певну кількість годин
    public function calculateRentalCost($hours)
    {
        return $this->hourlyRate * $hours;
    }

    # Створення методу, що повертає тип транспортного засобу 
    public function getType()
    {
        return "motorcycle";
    }
}

# Створення класу CreditCardPayment (оплати кредитною картою), що імлементує інтерфейс PaymentMethod 
class CreditCardPayment implements PaymentMethod
{
    private $cardNumber, $expirationDate;

    public function __construct($cardNumber, $expirationDate)
    {
        if (empty($cardNumber)) {
            throw new RentalException("Invalid credit card number");
        }
        $this->cardNumber = $cardNumber;
        $this->expirationDate = $expirationDate;
    }

    # Створення методу для обробки процесу оплати 
    public function processPayment($amount)
    {
        if ($amount <= 0) {
            throw new RentalException("Payment amount must be greater than zero.");
        }

        echo "Processing credit card payment of $$amount\n";
    }
}

# Додаткові класи платіжних методів, що реалізують інтерфейс PaymentMethod 

# Клас, що реалізує оплату готівкою
class CashPayment implements PaymentMethod
{
    public function processPayment($amount)
    {
        if ($amount <= 0) {
            throw new RentalException("Payment amount must be greater than zero.");
        }

        echo "Processing cash payment of $$amount\n";
    }
}

# Клас, що реалізує оплату за допомогою PayPal
class PayPalPayment implements PaymentMethod
{
    private $email;
    private $password;

    public function __construct($email, $password)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new RentalException("Invalid email address.");
        }

        if (empty($password)) {
            throw new RentalException("Password cannot be empty.");
        }

        $this->email = $email;
        $this->password = $password;
    }

    public function processPayment($amount)
    {
        if ($amount <= 0) {
            throw new RentalException("Payment amount must be greater than zero.");
        }

        echo "Processing PayPal payment of $$amount for user {$this->email}\n";
    }
}

# Клас, що реалізує оплату банківським переказом
class BankTransferPayment implements PaymentMethod
{
    private $bankAccount;
    private $IBAN;

    public function __construct($bankAccount, $IBAN)
    {
        $this->bankAccount = $bankAccount;
        $this->IBAN = $IBAN;

        if (empty($bankAccount) || !preg_match('/^\d{10,12}$/', $bankAccount)) {
            throw new RentalException("Invalid bank account number.");
        }

        if (empty($IBAN) || !preg_match('/^[A-Z]{2}\d{2}[A-Z0-9]{1,30}$/', $IBAN)) {
            throw new RentalException("Invalid IBAN.");
        }
    }

    public function processPayment($amount)
    {
        if ($amount <= 0) {
            throw new RentalException("Payment amount must be greater than zero.");
        }

        echo "Processing bank transfer of $$amount from account {$this->bankAccount} (IBAN: {$this->IBAN})\n";
    }
}

# Клас, що реалізує оплату за допомогою мобільного телефона
class MobilePayment implements PaymentMethod
{
    private $phoneNumber;
    private $service;

    public function __construct($phoneNumber, $service)
    {
        if (empty($phoneNumber)) {
            throw new RentalException("Invalid phone number.");
        }

        if (empty($service) || !in_array($service, ['Apple Pay', 'Google Pay'])) {
            throw new RentalException("Invalid payment service.");
        }

        $this->phoneNumber = $phoneNumber;
        $this->service = $service;
    }

    public function processPayment($amount)
    {
        if ($amount <= 0) {
            throw new RentalException("Payment amount must be greater than zero.");
        }

        echo "Processing mobile payment of $$amount via {$this->service} for phone {$this->phoneNumber}\n";
    }
}

# Клас, що реалізує оплату за допомогою криптовалют
class BitcoinPayment implements PaymentMethod
{
    private $walletAddress;

    public function __construct($walletAddress)
    {
        if (empty($walletAddress)) {
            throw new RentalException("Invalid Bitcoin wallet address.");
        }

        $this->walletAddress = $walletAddress;
    }

    public function processPayment($amount)
    {
        if ($amount <= 0) {
            throw new RentalException("Payment amount must be greater than zero.");
        }

        echo "Processing Bitcoin payment of $$amount to wallet {$this->walletAddress}\n";
    }
}


## Частина 3: Створення системи оренди

/* 6. Створіть клас `RentalSystem`:
   - Додайте приватну властивість `$vehicles` (масив)
   - Реалізуйте методи `addVehicle()` та `rentVehicle()` */

# Створення класу для обробки винятків 
class RentalException extends Exception
{
    public function __construct($message, $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}

# Створення класу для обробки системи оренди
class RentalSystem
{
    private $vehicles = [];

    # Створення методу для додавання нового транспортного засобу
    public function addVehicle(Vehicle $vehicle)
    {
        try {
            foreach ($this->vehicles as $existingVehicle) {
                if ($existingVehicle->getInfo() === $vehicle->getInfo()) {
                    throw new RentalException("Vehicle already exists in the system.");
                }
            }

            $this->vehicles[] = $vehicle;
        } catch (RentalException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    # Створення методу для обчислення вартості за оренду транспортного засобу
    public function rentVehicle($vehicleIndex, $time, PaymentMethod $paymentMethod)
    {
        try {
            if (!isset($this->vehicles[$vehicleIndex])) {
                throw new RentalException("Vehicle not available for rent.");
            }
            $vehicle = $this->vehicles[$vehicleIndex];
            $rentalCost = 0;

            if ($vehicle->getType() === "car") {
                $rentalCost = $vehicle->calculateRentalCost($time);
                echo "Vehicle info: " . $vehicle->getInfo() . "\n";
                echo "Rental cost for $time days: $$rentalCost\n" . "/ ";
            }
            if ($vehicle->getType() === "motorcycle") {
                $rentalCost = $vehicle->calculateRentalCost($time);
                echo "Vehicle info: " . $vehicle->getInfo() . "\n";
                echo "Rental cost for $time hours: $$rentalCost\n" . "/ ";
            }
            $paymentMethod->processPayment($rentalCost);
        } catch (RentalException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        } catch (Exception $e) {
            echo "An unexpected error occurred: " . $e->getMessage() . "\n";
        }
    }

    # Додаткові методи

    # Створення методу для перегляду усіх транспортних засобів наявних у системі
    public function listVehicles()
    {
        if (empty($this->vehicles)) {
            echo "No vehicles available.\n";
            return;
        }

        foreach ($this->vehicles as $index => $vehicle) {
            echo "[$index] " . $vehicle->getInfo() . "<br>";
        }
    }

    # Створення методу для резерву транспортного засобу
    private $reservations = [];

    public function reserveVehicle($vehicleIndex, $date, $user)
    {
        try {
            if (!isset($this->vehicles[$vehicleIndex])) {
                throw new RentalException("Vehicle not available for reservation.");
            }

            if (isset($this->vehicles[$vehicleIndex])) {
                $this->reservations[] = [
                    'vehicle' => $this->vehicles[$vehicleIndex],
                    'date' => $date,
                    'user' => $user
                ];
                echo "Vehicle reserved successfully for $date.\n";
            } else {
                echo "Vehicle not available for reservation.\n";
            }
        } catch (RentalException $e) {
            echo "Error: " . $e->getMessage() . "\n";
        }
    }

    # Створення методу для перегляду усіх резервів
    public function listReservations()
    {
        if (empty($this->reservations)) {
            echo "No reservations found.\n";
            return;
        }

        foreach ($this->reservations as $reservation) {
            echo "Vehicle: " . $reservation['vehicle']->getInfo() . ", Reserved for: " . $reservation['date'] . ", User: " . $reservation['user'] . "<br>";
        }
    }
}

## Частина 4: Демонстрація роботи системи

/* 7. Напишіть код для демонстрації роботи системи:
   - Створіть екземпляр `RentalSystem`
   - Додайте до системи кілька транспортних засобів
   - Створіть екземпляр `CreditCardPayment`
   - Продемонструйте оренду різних транспортних засобів */

$rentalSystem = new RentalSystem(); // Створення екземпляра класу `RentalSystem`;

$rentalSystem->addVehicle(new Car("Toyota", "Corolla", 2020, 55)); // Додавання нового транспортного засобу
$rentalSystem->addVehicle(new Car("Ford", "Mustang", 2022, 85));
$rentalSystem->addVehicle(new Motorcycle("Suzuki", "GSX-R1000", 2020, 5));
$rentalSystem->addVehicle(new Motorcycle("Yamaha", "Tenere 700", 2021, 7));
$rentalSystem->addVehicle(new Car("Dodge", "Challenger", 2021, 150));
$rentalSystem->addVehicle(new Motorcycle("BMW", "F 900 R", 2022, 60));

$creditCardPaymaentMethod = new CreditCardPayment("1234-5678-9012-3456", "12/25"); // Додавання нового екземпляру класу `CreditCardPayment`
$cashPaymentMethod = new CashPayment();
$payPalPaymentMethod = new PayPalPayment("user12@gmail.com", "H3x@mpl3P@ss");
$bankTransferPaymentMethod = new BankTransferPayment("3048572910", "UA29NWBK60161331926819");
$mobilePaymentMethod = new MobilePayment("0677391826", "Google Pay");
$bitcoinPaymentMethod = new BitcoinPayment("0x7A3B2F8E4C91a8dD3B9c456E76F0E2cA91F9e123");

$rentalSystem->rentVehicle(0, 5, $creditCardPaymaentMethod); // Оренда першого транспортного засобу на 5 днів з оплатою по карті
echo "<br>";
$rentalSystem->rentVehicle(1, 3, $cashPaymentMethod); // Оренда другого транспортного засобу на 3 дні з оплатою готівкою
echo "<br>";
$rentalSystem->rentVehicle(2, 10, $payPalPaymentMethod); // Оренда третього транспортного засобу на 10 годин з оплатою за допомогою PayPal
echo "<br>";
$rentalSystem->rentVehicle(3, 13.3, $bankTransferPaymentMethod);
echo "<br>";
$rentalSystem->rentVehicle(4, 2, $mobilePaymentMethod);
echo "<br>";
$rentalSystem->rentVehicle(5, 10, $bitcoinPaymentMethod);
echo "<br>";

echo "<br> Список усіх транспортних засобів: <br>";
$rentalSystem->listVehicles();
echo "<br>";

$rentalSystem->reserveVehicle(3, "03-12-2024", "Kevin Stuart");
echo "<br>";

echo "<br> Список зарезервованих транспортних засобів: <br>";
$rentalSystem->listReservations();

?>