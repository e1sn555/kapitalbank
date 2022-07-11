# Kapitalbank Payment API with PHP (Laravel Framework)

### Məlumat
Bu paket Laravel layihələrinizə Kapitalbankın ödəniş sistemini rahatlıqla bağlamağınıza köməklik edəcəkdoir.

### Quraşdırma
```shell 
composer require e1sn555/kapitalbank
```
Siz bu kodu layihənin olduğu qovluqda çalışdıraraq paketi layihəyə daxil edə bilərsiniz.

### Laravel avto-yükləyicisi olmadan paketi tanıtmaq
Əgər siz paketi yükləmisinizsə, lakin yenədə layihə paketi görmürsə və ya siz onu manual olaraq tanıtdırmaq istəyirsinizsə paketin ServiceProvider'ini `config/app.php` daxilində müvafiq yerə yazmalısınız.
```php 
    Kapitalbank\KapitalbankServiceProvider::class
```
Əgər siz Facade istifadə etmək istəyirsinizsə onda aşağıdakı koduda daxil etməlisiniz.
```php
    'Kapitalbank' => Kapitalbank\KapitalbankFacade::class 
```

### Konfiqurasiya
Paketi konfiqurasiya etmək üçün əvvəlcə konfiq fayıllarını çağırmalısınız. Bunun üçün aşağıdakə sətri icra etməyiniz bəs edir.
```shell
    php artisan vendor:publish --provider=Kapitalbank\KapitalbankServiceProvider
```
Bu kodun icrasında sizin `config` qovluğunuza `kapitalbank.php` adlı yeni bir fayl gələcəkdir. Bu konfiq faylının daxili aşağıdakı kimidir.

```php 
    return [
        /**
         * Merchant id
         */
        'merchant' => '',
    
        /**
         * Certificate path
         */
        'certificate_path' => '',
    
        /**
         * Key path
         */
        'key_path' => '',
    
        /**
         * Approve callback url
         */
        'approve_url' => '',
    
        /**
         * Cancel callback url
         */
        'cancel_url' => '',
    
        /**
         * Decline callback url
         */
        'decline_url' => ''
    ];
```
Siz burada `merchant`, `certificate_path` və `key_path` yazılan xanaları məcburi şəkildə doldurmalısınız (Siz kapitalbank əməkdaşına `key` yaradıb verirsiz o isə sizə `certificate` və `merchant_id` təqdim edəcəkdir). `approve_url`, `cancel_url` və `decline_url` isə default callback vermək üçün nəzərdə tutulub. Əgər siz bunu default şəkildə vermək istəmirsinizsə sorğu zamanıda verə bilərsiniz.

### Servisi kontrollerimizə tanıtmaq (Dependency Injection)

```php 
    class OrderController {
        public function __construct(private Kapitalbank\Kapitalbank $kapitalbank) {}
    }
```
Servisi kontrollerin `__construct()` metodunda bu şəkildə təyin etməyiniz servisdən istifadə etməyiniz üçün kifayət edəcəkdir.

### İstifadə qaydaları
Hazırda paketdən istifadə edərək `purchase`, `refund` və `preAuth` sorğularını istifadə edə bilərsiniz. Bu haqda daha çox məlumat almaq üçün <a href="https://pg.kapitalbank.az/docs">https://pg.kapitalbank.az/docs saytını ziyarət edə bilərsiniz.


##### Purchase sorğusu
Bu sorğuda siz müştərinin hesabından pulu bir başa olaraq öz hesabınıza çəkəcəksiniz. Nümunə aşağıdakı kimidir:

```php 
    public function createOrder(Request $request)
    {
        $response = $this->kapitalbank->createOrder(amount: $request->amount, description: 'X ayaqqabı üçün ödəniş');
        
        if($response->failed()) {
            $response->errors(function ($response, $e) {
                //
            })
            // Servisə sorğu zamanı yarana biləcək xətaların ələ alınması
        }
        
        // Servisdən qayıdan cavabı ələ almaq. Qayıdan cavabları daha dəqiq bilmək üçün Kapitalbankın yuxarıda verilən rəsmi saytına göz ata bilərsiniz.
        $response->toArray();
    }
```

##### Refund sorğusu
Siz sorğu vasitəsilə pulu müştərinin hesabına qaytarırsız. Bu isə aşağıdakı kimi icra olunur:

```php 
    public function refund(Request $request)
    {
        $response = $this->kapitalbank->refund(amount: $request->amount, session_id: $request->session_id, order_id: $request->order_id, description: 'X ayaqqabının geri qaytarılması');
        
        if($response->failed()) {
            // Servisə sorğu zamanı yarana biləcək xətaların ələ alınması
        }
        
        // Servisdən qayıdan cavabı ələ almaq. Qayıdan cavabları daha dəqiq bilmək üçün Kapitalbankın yuxarıda verilən rəsmi saytına göz ata bilərsiniz.
        $response->toArray();
    }
```

##### PreAuth sorğusu
Bu sorğunun əsas məqsədi müştərinin hesabında olan pulu bloklamaqdır. Yəni siz müştəri məhsulu sifariş etdiyi anda pulu bloklayır, məhsulu ona çatdırdığınız anda isə pulu öz hesabınıza keçirirsiz və ya anbarınızda məhsul tükənibsə həmin sifariş ləğv edir, pulu da blokdan açırsınız. PreAuth sorğusu buna görədə 3 hissədən ibarətdir: `sifariş yaratma`, `sifarişi tamamlama`, `sifarişi ləğv etmə`

###### Sifariş yaratma
```php 
    public function createOrder(Request $request)
    {
        $response = $this->kapitalbank->createOrder(amount: $request->amount, description: 'X ayaqqabı üçün preAuth sorğusu yaratmaq', order_type: 'PreAuth');
        
        if($response->failed()) {
            // Servisə sorğu zamanı yarana biləcək xətaların ələ alınması
        }
        
        // Servisdən qayıdan cavabı ələ almaq. Qayıdan cavabları daha dəqiq bilmək üçün Kapitalbankın yuxarıda verilən rəsmi saytına göz ata bilərsiniz.
        $response->toArray();
    }
```
Gördüyünüz kimi bu sorğu ilə birinci sorğu arasındaki tək fərq `order_type` göndərməyimizdir. Qayıdan cavab da birinci sorğunun cavabı ilə eyni olacaqdır.

###### Sifarişi tamamlama
```php 
    public function completePreAuth(Request $request)
    {
        $response = $this->kapitalbank->completePreAuth(amount: $request->amount, description: 'X ayaqqabı üçün preAuth sorğusunu tamamlamaq', order_id: $request->order_id, session_id: $request->session_id);
        
        if($response->failed()) {
            // Servisə sorğu zamanı yarana biləcək xətaların ələ alınması
        }
        
        // Servisdən qayıdan cavabı ələ almaq. Qayıdan cavabları daha dəqiq bilmək üçün Kapitalbankın yuxarıda verilən rəsmi saytına göz ata bilərsiniz.
        $response->toArray();
    }
```

###### Sifarişi ləğv etmək
```php 
    public function cancelPreAuth(Request $request)
    {
        $response = $this->kapitalbank->cancelPreAuth(order_id: $request->order_id, session_id: $request->session_id, description: 'X ayaqqabı üçün preAuth sorğusunu ləğv etmək');
        
        if($response->failed()) {
            // Servisə sorğu zamanı yarana biləcək xətaların ələ alınması
        }
        
        // Servisdən qayıdan cavabı ələ almaq. Qayıdan cavabları daha dəqiq bilmək üçün Kapitalbankın yuxarıda verilən rəsmi saytına göz ata bilərsiniz.
        $response->toArray();
    }
```


##### Taksitə görə aylaraq bölmək
Kapitalbank tərəfindən sizin üçün ayrılan ayları əvvəlcə müştəriyə seçdirməli daha sonra isə taksit sorğusu yaratmalısınız. Taksik sorğusu yaratmaq çox asandır, sadəcə `description` hissəsində `TAKSIT=$ay` kimi göndərəcəksiniz. Numünə aşağıdaki kimi olacaqdır:

```php 
    public function createOrder(Request $request)
    {
        $response = $this->kapitalbank->createOrder(amount: $request->amount, description: "TAKSIT=$request->month");
        
        if($response->failed()) {
            // Servisə sorğu zamanı yarana biləcək xətaların ələ alınması
        }
        
        // Servisdən qayıdan cavabı ələ almaq. Qayıdan cavabları daha dəqiq bilmək üçün Kapitalbankın yuxarıda verilən rəsmi saytına göz ata bilərsiniz.
        $response->toArray();
    }
```
