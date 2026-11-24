# Dependency Injection voor Core en IPF — analyse en voorstel

**Status:** voorstel, nog geen code gewijzigd
**Uitgangspunten:** PHP 8.4 als ondergrens (zie 3.1), `symfony/dependency-injection` (7.4 LTS of 8.x) als container, BC blijft een harde eis
**Scope:** `htdocs/libraries/Icms/Core/` (vooral `Entity`, `EntityHandler`), `htdocs/libraries/Icms/Ipf/` (Entity, Handler, Controller, subhandlers, Form, View) en de handler-loaders `icms::handler()` en `icms_getModuleHandler()`.

---

## 1. Inventaris: hoe komen klassen nu aan hun afhankelijkheden?

### 1.1 De mechanismen

| Mechanisme | Voorbeeld | Waar |
|---|---|---|
| **Statische service-locator** | `\icms::$module`, `\icms::$user`, `\icms::$urls`, `\icms::$xoopsDB` | `libraries/icms.php:3-23` (de properties), overal gebruikt |
| **Statische handler-registry** | `\icms::handler('icms_member_groupperm')` | `libraries/icms.php` → `handler()`; maakt `new $class(self::$xoopsDB)` en cachet |
| **Module-handler-loader** | `icms_getModuleHandler('category', $dir)` | `include/functions.php:1595-1638`; zoekt klasse op naamconventie, include't bestand, `new $class(icms::$xoopsDB)` |
| **Globals** | `global $icmsConfig, $icmsModuleConfig, $xoTheme, $icmsTpl, $myts` | zie 1.2 |
| **Statische helpers / singletons** | `\Icms\Core\DataFilter::checkVarArray()`, `Textsanitizer::getInstance()`, `EditorHandler::getInstance()` | zie 1.2 |
| **Superglobals** | `$_POST`, `$_GET`, `$_REQUEST`, `$_COOKIE` | `Ipf/Controller.php`, `Ipf/View/Table.php`, `Ipf/Permission/Handler.php` |
| **Hard `new`** | `new \Icms\Ipf\Permission\Handler($this->handler)`, `new \Icms\Ipf\Controller(...)` | `Ipf/Entity.php`, `Ipf/Handler.php`, `Ipf/Controller.php` |

Opvallend: **de kern heeft al een proto-container.** `icms::$services` (`libraries/icms.php:13-23`) is een
tabel van servicenaam → factory, en `icms::loadService()` instantieert die en vuurt een `loadService`-event.
Dat is conceptueel een (niet-gecompileerde, niet-autowirende) container met statische toegang. Het
DI-voorstel kan daar dus op aansluiten in plaats van iets compleet nieuws te introduceren.

En: **een deel is al constructor-injectie.** `Core\EntityHandler::__construct($db)` (`Core/EntityHandler.php:80`),
`Ipf\Handler::__construct($db, $itemname, …)` (`Ipf/Handler.php:162`), `Ipf\Entity::__construct(&$handler)`
(`Ipf/Entity.php:61`), `Ipf\Controller::__construct($handler)` (`Ipf/Controller.php:39`). Het probleem zit
niet in de constructors maar in alles wat *daarna* via `icms::`, globals en `new` binnenkomt.

### 1.2 Per klasse

| Klasse | Verborgen afhankelijkheden | Belangrijkste plaatsen |
|---|---|---|
| `Ipf\Handler` | `icms::handler('icms_member_groupperm')`, `icms::handler('icms_module')`, `icms_getModuleInfo()`, `icms_getModuleConfig()`, `new Permission\Handler($this)`, constanten `ICMS_ROOT_PATH/URL/UPLOAD_*` | `Handler.php:175-198` (paden in constructor), `:231`, `:665-666`, `:949`, `:956` |
| `Ipf\Entity` | `icms_loadLanguageFile()` *bij het laden van het bestand*, `global $icmsModuleConfig`, `Textsanitizer::getInstance()`, `icms::handler('icms_data_urllink')`, `icms::handler('icms_data_file')`, `new Permission\Handler`, `new Controller` (7×), `new Form\Base/Secure`, `new View\Single/Row` | `Entity.php:19`, `:81`, `:376`, `:387`, `:402`, `:509`, `:596-647`, `:707-764`, `:1111`, `:1225-1268` |
| `Ipf\Controller` | `$_POST`/`$_REQUEST`/`$_GET` (± 40×), `icms::$urls`, `icms::$module`, `global $icmsTpl`, `global $icmsConfig`, `redirect_header()`, `icms_getModuleInfo()`, `new Permission\Handler` | `Controller.php:55-145`, `:234`, `:243`, `:263`, `:341-420`, `:618-627` |
| `Ipf\Permission\Handler` | `icms::handler('icms_member_groupperm')` (5×), `icms::$user`, `global $icmsModule`, `$_POST` | `Permission/Handler.php:59`, `:97`, `:131-151`, `:170`, `:198`, `:219-224` |
| `Ipf\Registry\Handler` | `icms::$module` + `icms_getModuleHandler()` (5×) | `Registry/Handler.php:104-215` |
| `Ipf\Category\Handler` | `icms::$user` | `Category/Handler.php:61` |
| `Ipf\Member\Handler` | `global $icmsConfig, $icmsConfigUser`, `new icms_messaging_Handler()`, `new icms_db_criteria_Item` | `Member/Handler.php:66`, `:83`, `:129` |
| `Ipf\Metagen` | `global $icmsModuleConfig` (4×), `global $xoopsTpl, $xoTheme`, `icms::$module` | `Metagen.php:147-369` |
| `Ipf\About` | `global $icmsConfig`, `icms::$module` (±15×), `icms::handler('icms_module')`, `icms_loadLanguageFile()` | `About.php:63-217` |
| `Ipf\View\Table` | `$_GET`, `$_POST`, `$_COOKIE`, `$_SERVER`, `icms::$urls`, `DataFilter::checkVarArray()` | `View/Table.php:127`, `:251-264`, `:469`, `:564-619`, `:733` |
| `Ipf\Form\Base` | `icms::handler('icms_member')`, `global $xoTheme`, `xoops_getenv()`, element-klasse via naamconventie + `include_once` | `Form/Base.php:49`, `:273`, `:400-447`, `:668` |
| `Ipf\Form\Elements\*` | `icms::$xoopsDB` (rauwe SQL in `User`), `icms::handler(...)` (`Page`, `Richfile`, `Urllink`, `Select`, `Radio`, `Checkbox`), `icms_getModuleHandler()`, `EditorHandler::getInstance()`, `global $icmsConfig/$xoTheme`, `icms_loadLanguageFile()` | `Elements/User.php:33-36`, `Select.php:55-57`, `Source.php:31-37`, `Page.php:26-43`, … |

### 1.3 Twee harde technische beperkingen

1. **Vaste constructor-handtekening van module-handlers.** Zowel `icms::handler()` als
   `icms_getModuleHandler()` doen letterlijk `new $class(icms::$xoopsDB)`. Elke module-handler
   (bv. `SystemAdsenseHandler::__construct(&$db)`, `modules/system/admin/adsense/class/adsense.php:216`)
   rekent daarop en roept zelf `parent::__construct($db, 'adsense', 'adsenseid', 'tag', 'description', 'system')`.
   Extra constructorparameters toevoegen aan `Ipf\Handler` breekt **alle** bestaande module-handlers.

2. **By-reference parameters (`&$db`, `&$handler`).** 9 constructors nemen `&$db`, 13 nemen `&$handler`.
   Een gecompileerde Symfony-container genereert code als
   `new X($container->services['db'] ?? self::getDbService($container))`. Op PHP 8.x is dat een fatale fout:

   ```
   Error: A::__construct(): Argument #1 ($db) could not be passed by reference
   ```

   (geverifieerd met `php -r` op 8.3; gedrag is identiek op 8.4). Legacy handlers kunnen dus **niet direct**
   als Symfony-service gedefinieerd worden; er is een factory tussen nodig die eerst een variabele maakt.
   Objecten by-reference doorgeven heeft sinds PHP 5 geen zin meer; de `&` moet op termijn weg, maar dat
   is een BC-breuk voor subklassen die de handtekening overnemen (LSP-check).

### 1.4 Mapping van `icms::` naar Symfony DI

Conceptueel lijkt `icms::` sterk op een Symfony-container. In de uitvoering zit het verschil:
`icms::` lost alles **op tijdens het request**, met **namen als sleutel** en met **statische, globale
toegang**. Symfony lost alles op **tijdens het compileren**, met **types als sleutel**, en zonder globale
toegang.

| `icms::` vandaag | Symfony-equivalent | Verschil |
|---|---|---|
| `icms::$services["boot"]` = `naam => [[Klasse, 'methode'], $args]` (`libraries/icms.php:13-23`) | Service-definitie met een factory: `$s->set(Logger::class)->factory([Logger::class, 'instance'])->args([...])` | Bijna 1-op-1. Dit is het deel dat het meest op een container lijkt. |
| `icms::create('\\Klasse', $args)`, via `ReflectionClass::newInstanceArgs()` | Gewone definitie `$s->set(Klasse::class)->args([...])` of autowiring | Symfony genereert bij het compileren gewone code (`new Klasse(...)`): geen reflectie per request. |
| `icms::boot()` maakt **alle** boot-services meteen aan | Services zijn standaard **lazy**: ze worden pas gebouwd bij het eerste gebruik | Gedragsverschil, zie "boot-volgorde" hieronder. |
| `"optional" => []` | Geen echte tegenhanger. In Symfony is elke service "optioneel" tot iemand ze vraagt | — |
| `icms::$logger`, `icms::$db`, … (`public static`) | `$container->get(Logger::class)` op een **public** service, of beter: constructor-injectie | Symfony kent bewust geen statische toegang. Het dichtst erbij liggen Laravel-facades, niet Symfony. |
| Gedeelde instantie (de static property zelf) | `shared: true`, de standaard | Gelijk. |
| `icms::loadService()` **na** `boot()` (`launchModule()`; `include/cp_header.php:22` laadt `module` opnieuw met `admin=true`) | Een **synthetic service**: `$s->set('icms.module')->synthetic()`, en tijdens het request `$container->set('icms.module', $module)` | Na het compileren kun je in Symfony alleen synthetic services nog zetten. `icms::` laat elke service op elk moment overschrijven. |
| Event `loadService` / `loadService-<naam>` (`icms::loadService()`) | Afhankelijk van wat de listener doet: (a) een **configurator** (`->configurator([...])`), (b) een **decorator** (`->decorate(...)`), (c) een **compiler pass** | `icms::` reageert *tijdens het request* op het aanmaken van een object. Symfony configureert *vooraf*. |
| `icms::handler($naam)`: zoekt een klasse op naamconventie, include't bestanden, `new $class(self::$xoopsDB)`, cachet in `self::$handlers` | **Autowiring op type** voor vaste afhankelijkheden. Voor opzoeken op naam: een **`ServiceLocator`** (`tagged_locator('icms.handler', indexAttribute: 'name')`) | Symfony kent alle handlers vooraf (bestanden scannen met `load()`, of attributen). `icms::` ontdekt ze per request op het bestandssysteem. |
| `icms::handler($naam, $optional = true)` | `$container->has()` / `service(...)->nullOnInvalid()` / `?Type $x = null` met autowiring | Gelijkaardig. |
| `icms_getModuleHandler($item, $dir)` | Dezelfde `ServiceLocator`, met ID's als `icms.handler.<dir>.<item>` | Idem. |
| `icms::$paths`, `ICMS_ROOT_PATH`, `ICMS_URL`, … | **Parameters**: `%icms.root_path%`, of `env(...)` vanuit `mainfile.php` | Parameters liggen vast bij het compileren; constanten liggen vast bij de start van het request. Voor paden komt dat op hetzelfde neer. |
| `icms::$urls` (afgeleid van `$_SERVER`) | **Geen** container-waarde, maar `RequestStack` / `Request` | Per request: hoort niet in een gedeelde, gecompileerde container. |
| `icms::$user` | `Security::getUser()` / `TokenStorage`: een service die de gebruiker *aanbiedt* | Symfony injecteert nooit de gebruiker zelf, alleen een provider. |
| `icms::setup()` (autoload, paden, shutdown) | De constructor van `Kernel` en `public/index.php` | — |
| `icms::boot()` | `Kernel::boot()`, met daarin `initializeContainer()` | — |
| `icms::launchModule()` + `$module->registerClassPath()` | De bundles die de kernel registreert (`registerBundles()`) en hun `build()`/`boot()` | Een module ≈ een bundle. Module-install ≈ `cache:clear`. |
| Preloads + `icms_Event::attach/trigger` | `EventDispatcher` + `#[AsEventListener]` | `startCoreBoot`/`finishCoreBoot` komen ruwweg overeen met kernel-events. Symfony heeft geen events *tijdens* het bouwen van services. |
| `icms::shutdown()` | `kernel.terminate` / `Kernel::terminate()` | — |

#### Drie plaatsen waar de mapping niet triviaal is

**1. Het `loadService`-event wordt écht gebruikt, met neveneffecten die van de volgorde afhangen.**

- `libraries/Icms/Core/Security.php:67`: `Security::service()` koppelt `checkBadips` aan
  `loadService-config`. De bad-IP-check draait dus *omdat* de config later geladen wordt.
- `plugins/preloads/multilanguage.php:26` hangt aan `loadService-session`.
- `plugins/preloads/bootstrap.php` start timers als de logger aangemaakt wordt.

Met lazy services in Symfony bestaat het moment "service wordt aangemaakt" niet meer op een voorspelbaar
punt. Gewoon omzetten werkt dus niet. Aanpak:

- Het eigenlijke werk (bad-IP-check, taal initialiseren, timers) verhuizen naar **expliciete events** in
  de boot-volgorde, zoals `icms.boot.config_loaded` of een `kernel.request`-equivalent.
- `icms::loadService()` in de BC-laag de oude `loadService-*`-events **laten blijven vuren** wanneer
  `boot()` de static properties vult vanuit de container. Preloads van derden blijven dan werken.
- Voor services die echt bij het aanmaken geconfigureerd moeten worden: een `configurator`.

**2. Boot is nu eager en heeft neveneffecten.**
`Security::service()` doet `checkSuperglobals()` en kan `XOOPS_DB_PROXY` definiëren nog vóór de DB
bestaat. Met een lazy container hangt dat ervan af wie Security als eerste opvraagt. In de BC-laag moet
`boot()` de boot-services daarom expliciet in de huidige volgorde ophalen (`$container->get(...)` voor
elk). Dat behoudt het gedrag; luiheid win je pas later, per service.

**3. Runtime-overschrijven versus een immutabele container.**
`include/cp_header.php:22` vervangt de `module`-service midden in het request door een admin-variant.
In Symfony mag dat alleen met een synthetic service. Netter is een `ModuleContext`-service met een setter
(`setCurrent($module, admin: true)`): de container blijft immutabel, de toestand zit in een gewoon object.
Hetzelfde patroon geldt voor `$user` en `$urls`.

#### Concrete vertaling

```php
// Nu: libraries/icms.php
"logger" => [["icms_core_Logger", "instance"], []],
"config" => [["icms_config_Handler", "service"], []],

// Symfony: config/services.php
$s->set(\Icms\Core\Logger::class)
    ->factory([\Icms\Core\Logger::class, 'instance'])->public();
$s->set(\Icms\Config\Handler::class)
    ->factory([\Icms\Config\Handler::class, 'service'])->public();
$s->set(\Icms\Module\ModuleContext::class)->public();   // vervangt runtime-loadService('module')

// BC-laag in icms::boot()
foreach (self::BOOT_ORDER as $name => $id) {          // huidige volgorde bewaren
    self::$$name = self::$container->get($id);        // eager, zoals nu
    icms_Event::trigger('icms', 'loadService', null, ['name' => $name, 'service' => self::$$name]);
    icms_Event::trigger('icms', "loadService-$name", null, ['name' => $name, 'service' => self::$$name]);
}
```

Samengevat: de **definities** van `icms::$services` vertalen bijna mechanisch. Wat werk vraagt, zijn
de **runtime-gewoonten** rond die definities: services aanmaken met events, ze tijdens het request
overschrijven, en ze op naam en conventie ontdekken. Die gewoonten verdwijnen niet; ze verhuizen naar
expliciete events, context-services en een `ServiceLocator`.

---

## 2. Doelontwerp

### 2.1 Container en levenscyclus

- **Pakketten:** `symfony/dependency-injection`, `symfony/config` (loaders), `symfony/deprecation-contracts`
  (`trigger_deprecation()`). Optioneel later `symfony/event-dispatcher` en `symfony/http-foundation`.
- **Compileren en dumpen.** De `ContainerBuilder` wordt alleen gebouwd bij install/update/activatie van een
  module of bij een lege cache, en gedumpt met `PhpDumper` naar `ICMS_CACHE_PATH/container/IcmsContainer.php`.
  Per request wordt enkel die gecompileerde klasse geladen (geen reflectie, geen YAML-parsing).
- **Configuratie in PHP** (`ContainerConfigurator`), niet in YAML: geen extra parser, IDE-ondersteuning,
  en het past bij een codebase zonder YAML-traditie.
- **Defaults:** `autowire: true`, `autoconfigure: true`, `public: false`; alleen services die legacy code via
  `icms::container()->get()` moet kunnen ophalen worden `public`.
- **Bootstrap:** `icms::boot()` laadt de container en vult de bestaande statische properties
  (`icms::$xoopsDB`, `icms::$logger`, …) *vanuit* de container. Daardoor blijft alle bestaande code werken.

```php
// config/services.php (core)
return static function (ContainerConfigurator $c): void {
    $s = $c->services()->defaults()->autowire()->autoconfigure();

    $s->load('Icms\\Ipf\\Service\\', '../libraries/Icms/Ipf/Service/');

    // Bestaande factories hergebruiken — 1-op-1 vertaling van icms::$services["boot"]
    $s->set(\Icms\Db\Legacy\IDatabase::class)
        ->factory([\icms_db_Factory::class, 'instance'])->public();
    $s->alias('icms.db', \Icms\Db\Legacy\IDatabase::class)->public();

    $s->set(\Icms\Core\Logger::class)->factory([\Icms\Core\Logger::class, 'instance'])->public();
    $s->set(\Icms\Core\Security::class)->factory([\Icms\Core\Security::class, 'service'])->public();
    $s->set(\Icms\Core\Session::class)->factory([\Icms\Core\Session::class, 'service'])->public();
    $s->set(\Icms\Config\Handler::class)->factory([\Icms\Config\Handler::class, 'service']);

    // Runtime-waarden die pas tijdens het request bekend zijn
    $s->set(\Icms\Ipf\Service\RequestContext::class)->synthetic()->public();
};
```

### 2.2 Wat wordt een service, wat niet?

| Soort | Voorbeelden | Beslissing |
|---|---|---|
| Stateless infrastructuur | DB, Logger, Security, Session, ConfigHandler, Preload/events | **Service** (singleton in container) |
| Handlers (repositories) | `Ipf\Handler`-subklassen, `icms_member_groupperm`, `icms_module` | **Service**, via factory (zie 2.4) |
| Ipf-hulplogica | `Permission\Handler`, `Registry\Handler`, `Metagen`, `Export\Handler` | **Service** (Permission en Registry hebben nu `$handler` als toestand: zie 2.5) |
| Request-gebonden context | huidige user, huidige module, `icms::$urls`, `$_GET/$_POST` | **Geen** directe injectie van de *waarde*, wel van een **provider** (`CurrentUser`, `ModuleContext`, `RequestContext`) |
| Entities | `Ipf\Entity`-subklassen | **Geen service.** Aangemaakt door de handler |
| Forms, views, controllers | `Form\Base`, `View\Table`, `View\Single`, `Controller` | **Geen service**, wel via een **factory-service** die de afhankelijkheden injecteert |
| Pure functies | `DataFilter::htmlSpecialChars()`, `DataFilter::checkVar()` | Mogen **statisch blijven**; alleen de stateful delen (`Textsanitizer` met config, HTMLPurifier-config) worden een service |

Waarom providers en geen waarden: de container is gecompileerd en wordt gedeeld over requests. De huidige
gebruiker of module is per request anders en is er bij het bouwen van de handler soms nog niet
(`icms::launchModule()` loopt pas na `boot()`). Een `CurrentUser::get(): ?\Icms\Member\User\Entity` die
lazy naar de sessie kijkt, lost dat op en is in tests triviaal te vervangen.

### 2.3 Nieuwe, kleine services die uit IPF geëxtraheerd worden

| Service | Vervangt | Interface |
|---|---|---|
| `RequestContext` | `$_GET/$_POST/$_REQUEST/$_COOKIE`, `icms::$urls`, `xoops_getenv()` | `query()`, `post()`, `cookie()`, `currentUrl()`, `previousPage()` (of rechtstreeks `Symfony\Component\HttpFoundation\Request`) |
| `CurrentUser` | `icms::$user` + `ICMS_GROUP_ANONYMOUS`-fallback (komt 3× voor) | `get()`, `groups(): int[]`, `isAdmin()` |
| `ModuleContext` | `icms::$module`, `global $icmsModule`, `global $icmsModuleConfig`, `icms_getModuleInfo()`, `icms_getModuleConfig()` | `current()`, `info(string $dir)`, `config(string $dir)` |
| `HandlerLocator` | `icms::handler()`, `icms_getModuleHandler()` | `get(string $module, string $item)`; PSR-11-compatibel |
| `PermissionService` | `new Permission\Handler($handler)` + `icms::handler('icms_member_groupperm')` | `accessGranted(Handler $h, string $perm, int $id)`, `grantedItems(...)`, `save(...)` |
| `Redirector` | `redirect_header()` | `redirect(string $url, int $time, string $msg): never` |
| `TemplateProvider` | `global $icmsTpl/$xoopsTpl/$xoTheme` | `tpl()`, `theme()` |
| `Translator`/`LanguageLoader` | `icms_loadLanguageFile()` (ook top-level in `Ipf/Entity.php:19`) | `load(string $domain, string $file)` |

### 2.4 Handlers

Het lastige: een IPF-handler is *één klasse met configuratie* (itemnaam, sleutel, identifier, module),
gecombineerd met *services* (db, permissies, modulecontext). Vandaag zit dat allemaal in de constructor.

**Doel (nieuwe code):** configuratie als attribuut, services in de constructor.

```php
// NIEUW — moduleontwikkelaar
#[AsEntityHandler(module: 'news', item: 'article', key: 'article_id',
                  identifier: 'title', summary: 'summary')]
final class ArticleHandler extends \Icms\Ipf\Handler
{
    public function __construct(
        HandlerServices $services,          // bundel: db, permissions, module ctx, locator, events
        private readonly ArticleSlugger $slugger,   // eigen service van de module
    ) {
        parent::__construct($services);
    }
}
```

`HandlerServices` is een kleine *parameter object*-service die de 5-6 core-afhankelijkheden bundelt. Dat
houdt de constructor van subklassen kort en laat de core later afhankelijkheden toevoegen zonder elke
module te breken. Een compiler pass leest `#[AsEntityHandler]` en registreert de service onder
`icms.handler.news.article` en als FQCN.

**BC (bestaande code):** de oude constructor blijft werken; de services komen via setter-injectie of,
zonder container, via een lazy fallback.

```php
// Ipf\Handler — overgangsvorm
public function __construct(HandlerServices|\Icms\Db\Legacy\IDatabase $dbOrServices,
                            ?string $itemname = null, ?string $keyname = null,
                            ?string $idenfierName = null, ?string $summaryName = null,
                            ?string $modulename = null)
{
    if ($dbOrServices instanceof HandlerServices) {
        $this->services = $dbOrServices;
        $meta = EntityHandlerMetadata::fromAttribute(static::class);   // gecachet
        [$itemname, $keyname, $idenfierName, $summaryName, $modulename] = $meta->toArray();
        $db = $dbOrServices->db;
    } else {
        $db = $dbOrServices;   // legacy pad: services lazy via icms::container()
    }
    parent::__construct($db);
    // … bestaande initialisatie ongewijzigd …
}

#[\Symfony\Contracts\Service\Attribute\Required]
public function setServices(HandlerServices $services): void { $this->services = $services; }

protected function services(): HandlerServices
{
    return $this->services ??= \icms::container()->get(HandlerServices::class);
}
```

Legacy handlers met `__construct(&$db)` worden **niet** rechtstreeks door de container aangemaakt
(by-reference, zie 1.3) maar door een `LegacyHandlerFactory`:

```php
final class LegacyHandlerFactory
{
    public function __construct(private IDatabase $db, private HandlerServices $services) {}

    public function create(string $class): object
    {
        $db = $this->db;               // variabele, dus by-ref werkt
        $handler = new $class($db);
        if ($handler instanceof \Icms\Ipf\Handler) {
            $handler->setServices($this->services);
        }
        return $handler;
    }
}
```

`icms_getModuleHandler()` en `icms::handler()` worden dunne wrappers rond `HandlerLocator`: eerst de
container (`has("icms.handler.$dir.$name")`), anders het bestaande zoek-op-naamconventie-pad, dat dan via
`LegacyHandlerFactory` instantieert. De statische cache in beide functies verdwijnt; die rol neemt de
container over (shared services).

### 2.5 Entities

Een entity is een *waarde met gedrag*, geen service. Ze wordt per databankrij aangemaakt
(`Ipf\Handler::create()`, `Handler.php:248-262`, `new $this->className($this)`). Entities laten autowiren
zou betekenen dat de container honderden objecten per request bouwt; dat is niet de bedoeling.

**Regel: de handler is de enige afhankelijkheid van een entity.** Alles wat een entity nu via `icms::`,
`global` of `new` ophaalt, wordt een methode op de handler (die zelf geïnjecteerd is):

| Nu in `Ipf\Entity` | Wordt |
|---|---|
| `new Permission\Handler($this->handler)` → `accessGranted()` (`:81`) | `$this->handler->permissions()->accessGranted($this->handler, $perm, $this->id())` |
| `icms::handler('icms_data_urllink')` (`:1225`, `:1241`) | `$this->handler->locator()->get('icms', 'data_urllink')` of een dedicated `UrlLinkRepository` op `HandlerServices` |
| `global $icmsModuleConfig` (`:707`, `:723`) | `$this->handler->moduleConfig()` |
| `Textsanitizer::getInstance()` (`:710`, `:764`) | `$this->handler->services()->sanitizer` |
| `new Controller($this->handler)` (7×, `:402`–`:647`) | `$this->handler->controller()` (factory) |
| `new Form\Base(...)` (`:376`, `:387`) | `$this->handler->forms()->create($this, …)` |
| `icms_loadLanguageFile()` op bestandsniveau (`:19`) | weg; de `Translator` laadt bij het bouwen van de container of lazy in `HandlerServices` |

De constructor `__construct(&$handler)` blijft ongewijzigd, want *elke* module-entity roept
`parent::__construct($handler)` en definieert daarna zijn velden met `quickInitVar()`
(bv. `SystemAdsense`, `adsense.php:30-50`). Hydratatie (`assignVars()`) blijft in de handler.

### 2.6 Controller, Forms en Views

Deze objecten krijgen **runtime-argumenten** (het object, de formuliernaam, criteria, …) *en* services.
Dat is het klassieke geval voor een factory-service:

```php
final class ControllerFactory
{
    public function __construct(
        private RequestContext $request,
        private PermissionService $permissions,
        private Redirector $redirector,
        private TemplateProvider $templates,
        private Security $security,
    ) {}

    public function for(\Icms\Ipf\Handler $handler): Controller
    {
        return new Controller($handler, $this->request, $this->permissions,
                              $this->redirector, $this->templates, $this->security);
    }
}
```

- **Controller** leest geen `$_POST` meer maar `$this->request->post()`. Dit is meteen de grootste winst in
  testbaarheid: `postDataToObject()`, `storeFromDefaultForm()` en `handleObjectDeletion()` worden testbaar
  zonder superglobals te muteren.
- **View\Table / View\Single / View\Tree**: `TableViewFactory::create($handler, $criteria, $actions, $userSide)`
  injecteert `RequestContext` (sort/order/limit uit GET/cookie) en `TemplateProvider`.
- **Form\Base**: `FormFactory::create($entity, $name, $caption, $action, …)`. De resolutie van form-elementen
  (`Form/Base.php:400-447`: naamconventie + `include_once` + `ucfirst($module) . ucfirst($control) . 'Element'`)
  wordt een **`FormElementRegistry`** met getagde services (`icms.form_element`, attribuut
  `#[AsFormElement('select')]`). Modules registreren eigen controls in hun eigen `services.php` in plaats
  van een bestand op een conventionele plek te zetten. Het conventiepad blijft als fallback.
- **Form-elementen** zelf blijven `new`-baar (ze krijgen `$object, $key`), maar hun services komen uit de
  registry: `Select`, `Radio`, `Checkbox` krijgen een `HandlerLocator`, `User` een `MemberHandler` in plaats
  van rauwe SQL op `icms::$xoopsDB` (`Elements/User.php:33-36`).

### 2.7 Modules registreren hun services

```
modules/news/
├── config/services.php        ← nieuw, optioneel
└── class/ArticleHandler.php
```

```php
// modules/news/config/services.php
return static function (ContainerConfigurator $c): void {
    $c->services()->defaults()->autowire()->autoconfigure()
      ->load('Mod\\News\\', '../class/');
};
```

- Bij het compileren laadt de kernel `config/services.php` van **alle geïnstalleerde, actieve modules**
  (uit de `modules`-tabel). Moduleactivatie, -deactivatie en -update moeten dus de container-cache
  invalideren (hook in `icms_module_Handler` install/update/uninstall).
- Modules **zonder** `services.php` werken ongewijzigd via het legacy pad van `icms_getModuleHandler()`.
- Een module kan core-services *decoreren* (`->decorate(PermissionService::class)`): een uitbreidingspunt
  dat vandaag enkel via preloads of events kan.

### 2.8 Vergelijking voor en na

**`Ipf\Permission\Handler::accessGranted()`**, nu (`Permission/Handler.php:131-134`):

```php
$gperm_handler = \icms::handler('icms_member_groupperm');
$groups = is_object(\icms::$user) ? \icms::$user->getGroups() : array(ICMS_GROUP_ANONYMOUS);
```

Met DI:

```php
final class PermissionService
{
    public function __construct(
        private GroupPermHandler $groupPerms,
        private CurrentUser $user,
        private ModuleContext $modules,
    ) {}

    public function accessGranted(Handler $handler, string $perm, int $id): bool
    {
        $mid = $this->modules->idOf($handler->getModuleDirname());
        return $this->groupPerms->checkRight($perm, $id, $this->user->groups(), $mid);
    }
}
```

**Test, nu:** onmogelijk zonder `icms::$user` en `icms::$handlers` (protected static) te manipuleren
en zonder databank. **Met DI:**

```php
$perms = new PermissionService(
    $this->createMock(GroupPermHandler::class),   // checkRight → true
    new FixedCurrentUser(groups: [2]),
    new InMemoryModuleContext(['news' => 7]),
);
self::assertTrue($perms->accessGranted($handler, 'view', 42));
```

**Module-handler (`SystemAdsenseHandler`).** Nu en in de overgangsfase: **ongewijzigd.** Optioneel nieuw:

```php
#[AsEntityHandler(module: 'system', item: 'adsense', key: 'adsenseid',
                  identifier: 'tag', summary: 'description')]
class SystemAdsenseHandler extends \Icms\Ipf\Handler
{
    public function __construct(HandlerServices $services)
    {
        parent::__construct($services);
        $this->buildFormats();
    }
}
```

### 2.9 Site-brede preloads en events

Preloads worden **event listeners die bij het compileren ontdekt worden**. Daarbovenop komt een brug die
het bestaande preload-contract intact laat. De voorgestelde API's (`EventDispatcher`, `HttpKernel`-events,
`#[AsEventListener]`, compiler passes, `DirectoryResource`) zijn stabiel sinds Symfony 6.x/7.x en dus ook
bruikbaar in 8.x. Wat Symfony 8.2 specifiek wijzigt, is niet nagekeken.

#### Hoe preloads nu werken

- **Ontdekking per request.** `Icms\Preload\Handler::__construct()` (`libraries/Icms/Preload/Handler.php`)
  leest bij elk request `plugins/preloads/` uit met `DirectoryIterator`
  (`Core\Filesystem::getFileList()`). Daar is geen sortering zichtbaar, dus de volgorde hangt af van het
  bestandssysteem. Elk bestand wordt ge-include't. Voor een klasse `IcmsPreload<Naam>` wordt elke methode
  die met `event` begint gekoppeld aan het event in kleine letters
  (`eventFinishCoreBoot` → `finishcoreboot`).
- **Twee stijlen naast elkaar:**
  - `IcmsPreloadX extends icms_preload_Item` met `eventXxx()`-methodes: adsense, autotasks, customtag,
    googleanalytics, keepalive, protectEmail, protector, userinfo.
  - Bestanden die bij het includen `::setup()` aanroepen en zelf `icms_Event::attach()` doen:
    `bootstrap.php`, `autologin.php`, `debug_mode.php`, `multilanguage.php`. Die gebruiken ook
    `loadService-*` en de wildcard `'*'`.
- **Modulepreloads** via `global $icmsOnDemandPreload` en `modules/<dir>/preload/`.
- **Het contract voor callbacks** is `($params, \Icms\Event $event)`:
  - `params` bevat vaak **referenties**. Zo doet `Core/Textsanitizer.php:251`
    `triggerEvent('beforeDisplayTarea', [&$text, …])`, en past `protectEmail` `$array[0]` aan. Een preload
    is dus soms ook een **filter**.
  - Een eventnaam die met `*` begint, maakt het event annuleerbaar (`$event->canceled`).
- **Momenten in de opstart.** `startCoreBoot` vuurt in `icms::boot()` vóór er één service bestaat;
  `protector` draait daar zijn precheck, nog vóór de DB. Verder `finishCoreBoot`, `startOutputInit`
  (`header.php:69`), `beforeFooter` (`footer.php:60`) en domein-events (tekstfilters, `adminHeader`,
  `afterSaveSystemAdminPreferencesItems`).

#### De ICMS-events op de Symfony-levenscyclus

| ICMS-event | Moment in Symfony | Toelichting |
|---|---|---|
| `startCoreBoot` | Eigen event `icms.core.start_boot`, dat `Kernel::boot()` vuurt **direct na `initializeContainer()`** en vóór er services opgehaald worden | Symfony zelf heeft geen event vóór de container. Omdat services lazy zijn, is er op dit moment nog geen DB: dat gedrag van `protector` blijft behouden. |
| `loadService[-x]` | BC-laag in `boot()` (zie 1.4) | Blijft vuren terwijl de boot-services in de vaste volgorde opgehaald worden. |
| `finishCoreBoot` | `kernel.request` met hoge prioriteit (bv. 512), of een eigen `icms.core.finish_boot` aan het eind van de boot | Hier zitten nu de meeste listeners: protector-postcheck, autotasks, userinfo, … |
| `startOutputInit`, `beforeFooter`, `adminHeader` | Eigen events die de **theme-renderer** vuurt, eventueel vanuit `kernel.view` | Het zijn momenten in de rendering, geen momenten in de kernel. |
| Uitvoer bewerken (Smarty-resultaat, e-mailadressen beveiligen op paginaniveau) | `kernel.response` | Netter dan output buffering. |
| Domein-events (`beforeDisplayTarea`, `afterFilterHTMLdisplay`, `afterSaveSystemAdminPreferencesItems`, …) | Eigen **getypeerde event-klassen**, gedispatcht via `EventDispatcherInterface` | Niet gekoppeld aan de levenscyclus. |
| Autotasks bij een paginabezoek (`finishCoreBoot` + `exit(0)` in `plugins/preloads/autotasks.php`) | **`kernel.terminate`** | Functionele winst: de taken draaien nadat de response naar de bezoeker verstuurd is (`fastcgi_finish_request`). `exit()` in een listener breekt de response en de terminate-fase, dus die moet eruit. |

#### Ontdekken bij het compileren

Een compiler pass, of de `build()` van een `IcmsCoreBundle`, scant bij het compileren:

- `ICMS_PRELOAD_PATH`, voor de site-brede preloads;
- `modules/<dir>/preload/` van de **actieve** modules. Welke modules actief zijn, haal je bij het
  compileren liever niet uit de DB. Beter is een manifest dat module-install en -activatie wegschrijven,
  bv. `cache/modules.php`.

Per preload-klasse van de eerste stijl:

```php
foreach ((new \ReflectionClass($class))->getMethods() as $m) {
    if (!str_starts_with($m->name, 'event')) continue;
    $legacy = strtolower(substr($m->name, 5));            // 'finishcoreboot'
    $container->register("icms.preload.$file.$legacy", LegacyPreloadListener::class)
        ->setArguments([new Reference("icms.preload.$file"), $m->name])
        ->addTag('kernel.event_listener', [
            'event'    => LegacyEventMap::toSymfony($legacy),  // 'icms.core.finish_boot'
            'method'   => '__invoke',
            'priority' => $priorityFromFileOrder,           // vaste, alfabetische volgorde
        ]);
}
$container->addResource(new DirectoryResource(ICMS_PRELOAD_PATH, '/\.php$/'));
```

De `eventXxx()`-methodes verwachten `($params, $event)` en geen Symfony-event-object. Daarom tag je niet
het preload-object zelf, maar een `LegacyPreloadListener`-wrapper met het preload-object en de
methodenaam als argumenten.

Wat dit oplevert:

- **`DirectoryResource`** past precies bij het model "een bestand in `plugins/preloads` zetten". In
  debug-modus bouwt de container zichzelf opnieuw op zodra er een bestand bijkomt. In productie gebeurt
  dat bij een cache-reset, via een admin-actie of module-install.
- De preload-klassen worden **lazy services**: geen `include` en geen `new` per request meer voor preloads
  waarvan het event niet vuurt.
- De volgorde wordt **deterministisch**, via prioriteiten.
- Functionele extra: preloads per stuk **aan- en uitzetten** via een parameter (`icms.preloads.disabled`),
  zodat de compiler pass ze overslaat. Vandaag moet je daarvoor het bestand verwijderen.

#### De brug tussen `Icms\Event` en de `EventDispatcher`

`Icms\Event` blijft bestaan als façade, zodat de `setup()`-stijl, modules en
`icms::$preload->triggerEvent()` blijven werken.

```php
final class LegacyEvent extends \Symfony\Contracts\EventDispatcher\Event
{
    public array $parameters;
    public function __construct(
        public readonly string $namespace,
        public readonly string $name,
        public readonly mixed $source,
        array &$parameters,
        public readonly bool $canCancel,
    ) { $this->parameters = &$parameters; }   // referenties blijven werken
}

// Icms\Event::trigger() → dispatcher
$e = new LegacyEvent($ns, $name, $source, $params, $cancancel);
$this->wildcard->call($e);                         // '*'-listeners: Symfony kent geen wildcards
$this->dispatcher->dispatch($e, LegacyEventMap::toSymfony("$ns.$name"));

// Icms\Event::attach() → runtime-listener (toegestaan op EventDispatcher)
$this->dispatcher->addListener(LegacyEventMap::toSymfony("$ns.$name"),
    fn (LegacyEvent $e) => $callback($e->parameters, $e->legacyView()));
```

- **Annuleren** (`*`-prefix, `$event->canceled = true`) wordt `stopPropagation()`, alleen als
  `canCancel` aan staat.
- **Wildcards (`'*'`)**: `bootstrap.php` gebruikt die voor `backwardCompatibility`. Symfony heeft dat
  niet, dus de brug houdt zelf een kleine lijst bij en roept die vóór de dispatch aan.
- **Referenties**: omdat `LegacyEvent` de parameters by reference vasthoudt, blijft
  `$array[0] = convertEmail($array[0])` in `protectEmail` de originele tekst wijzigen.

#### Volgorde in `Kernel::boot()`

1. `initializeContainer()`. Die is gecompileerd, dus goedkoop.
2. Legacy preloads van de `setup()`-stijl includen. Hun `attach()` gaat nu naar `addListener()`. Dit moet
   **vóór** stap 3 en 4 gebeuren, want ze luisteren naar `loadService-*`.
3. `icms.core.start_boot` dispatchen. `protector` precheck draait hier, nog zonder DB.
4. De boot-services eager ophalen in de huidige volgorde en `loadService-*` vuren (BC).
5. `icms.core.finish_boot` dispatchen, of dat overlaten aan `kernel.request` als ImpressCMS volledig via
   `HttpKernel` draait.

#### De doelvorm voor nieuwe preloads

```php
#[AsEventListener]
final class ProtectEmailListener
{
    public function __construct(private ConfigProvider $config) {}

    public function __invoke(TextDisplayFiltered $event): void
    {
        if ($this->config->get('user', 'protect_email')) {
            $event->text = $this->convert($event->text);
        }
    }
}
```

- **Getypeerde events** (`TextDisplayFiltered` met een `public string $text`) vervangen de positionele
  arrays met referenties (`$array[0]` = tekst, `[1]` = html, …).
- De listener krijgt zijn afhankelijkheden in de constructor, in plaats van `global $icmsConfig` en
  `icms_getModuleHandler()`.
- Voor BC vuurt de brug voor elk getypeerd event ook de oude naam, met een `LegacyEvent` waarvan
  `parameters[0]` een referentie is naar `$event->text`.

#### Aandachtspunten

- **Cron en CLI:** preloads die op `finishCoreBoot` rekenen, draaien vandaag ook bij CLI-scripts die
  `common.php` laden. In Symfony horen die bij `ConsoleEvents::COMMAND`, of je dispatcht
  `icms.core.finish_boot` in beide kernels.
- **Preloads die bij het includen code uitvoeren** (de `setup()`-stijl) blijven een uitzondering: ze
  moeten elk request ge-include't worden. Migreer ze als eerste naar `#[AsEventListener]`, want het zijn
  er maar vier.

### 2.10 De meegeleverde preloads: wat kan weg, wat wordt standaard-Symfony

Uit de 13 meegeleverde preloads in `htdocs/plugins/preloads/` blijkt dat **geen enkele preload het
preload-mechanisme nodig heeft**. Ze vallen allemaal in drie categorieën, en voor elk daarvan heeft
Symfony een standaardoplossing:

- **dingen die de opstart beïnvloeden:** debug, timers, taal, autologin, protector;
- **tekstfilters:** adsense, customtag, protectEmail;
- **templatedata en scripts:** userinfo, adsense, customtag, GA, keepalive.

#### Per preload

| Preload | Wat het doet | Symfony-aanpak | Oordeel |
|---|---|---|---|
| `bootstrap.php` | Logger-timers starten bij `loadService(logger)` en stoppen bij `finishCoreBoot`. Ook een `'*'`-listener `backwardCompatibility()` die alleen `return true` doet. | `Stopwatch` + WebProfiler: in debug-modus worden kernel-fases en listeners automatisch gemeten. | **Weglaten.** De wildcard-listener is een no-op die bij elk event aangeroepen wordt. |
| `debug_mode.php` | Bij `loadService(config)`: `error_reporting()` aanpassen, de logger-weergave aanzetten, query-listeners koppelen. | `kernel.debug` / `APP_DEBUG`, `ErrorHandler`, WebProfiler, Monolog. Queries via een DBAL-middleware of een decorator op de DB-service. `$xoopsOption['nodebug']` wordt `$profiler->disable()`. | **Vervangen.** De listener op `icms_db_IConnection`/`prepare` is dood: geen enkele DB-klasse triggert `prepare`, alleen `execute`. |
| `multilanguage.php` | Bij `loadService(session)`: de taal kiezen uit `Accept-Language`, `?lang=`, een cookie of de sessie, en daarmee `global $icmsConfig['language']` overschrijven. | `framework.enabled_locales`, `set_locale_from_accept_language`, `Request::getPreferredLanguage()`, een eigen `LocaleListener` op `kernel.request` (cookie/sessie), `LocaleSwitcher` + Translation. | **Vervangen.** Bevat een bug: in de cookie-tak `$langs[$_GET['lang']]` terwijl `$_GET['lang']` daar niet gezet is. |
| `autologin.php` | Luistert naar `sessionStart`/`sessionClose`. Cookie `autologin_pass` = `datum:md5(wachtwoordhash . DB_PASS . prefix . datum)`, en POST-requests omleiden via `session_confirm.php`. | **`remember_me`** in de security-firewall: een signature-cookie met de wachtwoordhash, ondertekend met `kernel.secret`, of persistente tokens. | **Vervangen**, met voorrang: de md5-constructie met het DB-wachtwoord als sleutel is zwak. `stripSlashesGPC()` is sinds het verdwijnen van magic quotes (PHP 5.4) een no-op. De cookie wordt ook gezet in `include/checklogin.php:176` en gelezen door protector: die moeten mee. |
| `protector.php` | Bij `startCoreBoot` en `finishCoreBoot` de pre- en postcheck van de Protector-module includen vanuit `ICMS_TRUST_PATH`. | `kernel.request`-listener met hoge prioriteit, **in de Protector-module zelf**. Deels te vervangen door `RateLimiter` en firewall-regels, de rest door een WAF vóór de applicatie. | **Behouden als listener, verhuizen naar de module.** Het hoort niet in de core-preloads. |
| `autotasks.php` | Pseudo-cron bij elk paginabezoek (`finishCoreBoot`, eventueel `exit(0)`). Bij het opslaan van voorkeuren de taakplanner herstarten. | **Symfony Scheduler** + Messenger (echte cron of `messenger:consume`). Voor hosting zonder cron: een listener op `kernel.terminate`. De herstart wordt een listener op een getypeerd `ConfigSaved`-event. | **Vervangen.** `exit()` in een listener moet sowieso weg. |
| `adsense.php` | (a) taalbestand laden bij `finishCoreBoot`, (b) `[adsense]…[/adsense]` vervangen na het weergeven van tekst, (c) **bij elke pagina** alle adsenses uit de DB halen, renderen en aan Smarty toewijzen. | (a) Translator, lazy. (b) Een getagde **tekstfilter-service** (`icms.text_filter`) of een Twig-filter. (c) Een Twig-functie `adsense('tag')` die lazy laadt. | **Vervangen.** Bespaart een query + rendering per pagina. |
| `customtag.php` | Hetzelfde patroon als adsense, maar *vóór* het weergeven van tekst. `replaceTagDeterministic()` is copy-paste van adsense. | Idem: één gedeelde `BBTagReplacer`, twee getagde filters met prioriteit (customtag vóór, adsense na). | **Vervangen.** De dubbele code wordt één service. |
| `protectEmail.php` | E-mailadressen in gefilterde tekst vervangen door een afbeelding of reCAPTCHA Mailhide. | Een tekstfilter-service, of `kernel.response` voor de hele pagina. | **Vervangen en opkuisen.** De Mailhide-tak vraagt `mcrypt_encrypt`, dat sinds PHP 7.2 verwijderd is, en de dienst bestaat niet meer: die tak is dood. Ook bug: `$email` gaat zonder `preg_quote()` in regexen. |
| `userinfo.php` | Bij elke pagina alle uservars kopiëren naar `$xoopsTpl['user']`, met daarbij een COUNT-query op ongelezen privéberichten. | Twig-global **`app.user`**, standaard aanwezig. Ongelezen berichten via een lazy Twig-functie `unread_messages()`. | **Vervangen.** Eén query per pagina minder. |
| `googleanalytics.php` | GA-scripts toevoegen aan het theme bij `beforeFooter`. | Twig-template-include in het basislayout (`{% if ga_id %}…{% endif %}`), met de config als Twig-global. | **Vervangen.** De `UA-`-fallback is dood (Universal Analytics verwerkt sinds juli 2023 geen data meer), en de `else`-tak doet `echo 'error getting icmsConfigMetaFooter'`, ook als GA gewoon **uit** staat. |
| `keepalive.php` | Script toevoegen voor ingelogde gebruikers, in de footer en de admin-header. | Een conditie in het template: `{% if is_granted('IS_AUTHENTICATED') %}` + AssetMapper. | **Vervangen** door een templateconditie. |

#### Welke onderdelen van het mechanisme nog gebruikt worden

| Onderdeel | Gebruik in de core | Oordeel |
|---|---|---|
| `eventXxx()`-naamconventie | 9 preloads | Vervangen door `#[AsEventListener]`. De conventie blijft alleen in de BC-brug voor third-party preloads. |
| Koppelen bij het includen (`setup()`-stijl) | 4 preloads: bootstrap, autologin, debug_mode, multilanguage | **Weglaten.** Alle vier verdwijnen naar standaard-Symfony (zie de tabel hierboven). |
| `loadService-*`-events | bootstrap (logger), debug_mode (config), multilanguage (session), `Core/Security.php:67` (config) | In de core **niet meer nodig**: elk gebruik heeft een vervanger. Alleen nog vuren voor BC. |
| Wildcard `'*'` | Alleen de no-op in bootstrap | **Weglaten.** De wildcard-lijst in de brug uit 2.9 kan weg, of blijft beperkt tot een deprecation-pad voor derden. |
| Annuleerbare events (`*`-prefix, `$event->canceled`) | **Nergens** gebruikt | **Weglaten.** Wie het toch nodig heeft, gebruikt `stopPropagation()`. |
| `Event::current()`, `Event::detach()` | **Nergens** gebruikt | **Weglaten.** |
| `$icmsOnDemandPreload` | Wordt **nergens** gezet | **Weglaten.** |
| `modules/<dir>/preload/` | Geen enkele core-module heeft zo'n map | Alleen in de BC-brug voor third-party modules. Nieuw: listeners in de `services.php` van de module. |
| Parameters by reference (`[&$text, …]`) | protectEmail, adsense, customtag (tekstfilters); autotasks (voorkeuren) | Vervangen door **getypeerde, muteerbare events**. Voor tekst nog beter: een expliciete filterketen (zie hieronder). |
| `icms_preload_Item` (lege basisklasse) en `Preload\Handler::getInstance()` | Alle preloads van de eerste stijl | **Weglaten.** Listeners zijn gewone services. |
| Events in de DB-laag (`icms_db_IConnection`/`execute`) | Alleen debug_mode | Vervangen door een decorator of middleware op de DB-service. Dan hoeft niet elke query meer een event aan te maken. |
| Events rond de sessie (`sessionStart`/`sessionClose`) | Alleen autologin | Wegvallen met `remember_me`. |

#### Tekstfilters: een filterketen in plaats van events

De paren `before/afterDisplayTarea`, `…PreviewTarea`, `…FilterHTMLdisplay`, `…FilterHTMLinput`,
`…FilterTextareaDisplay` en `…FilterTextareaInput` (samen 12 trigger-plaatsen in `Textsanitizer` en
`DataFilter`) zijn in feite een **pijplijn**: elke listener krijgt de tekst, wijzigt die en geeft ze door.
Dat is de taak van een expliciete filterketen, niet van events.

```php
enum TextStage: string
{
    case BeforeDisplay = 'before_display';
    case AfterDisplay  = 'after_display';
    case BeforePreview = 'before_preview';
    case AfterPreview  = 'after_preview';
    // … input-varianten
}

#[AutoconfigureTag('icms.text_filter')]
interface TextFilter
{
    /** @return list<TextStage> */
    public static function stages(): array;
    public function filter(string $text, TextContext $ctx): string;   // ctx: html, smiley, xcode, image, br
}

final class TextFilterChain
{
    /** @param iterable<TextFilter> $filters gesorteerd op prioriteit */
    public function __construct(
        #[AutowireIterator('icms.text_filter', defaultPriorityMethod: 'priority')]
        private iterable $filters,
        private LegacyEventBridge $legacy,          // BC: oude eventnamen blijven vuren
    ) {}

    public function apply(TextStage $stage, string $text, TextContext $ctx): string
    {
        foreach ($this->filters as $filter) {
            if (in_array($stage, $filter::stages(), true)) {
                $text = $filter->filter($text, $ctx);
            }
        }
        return $this->legacy->filterText($stage, $text, $ctx);   // [&$text, …] voor oude preloads
    }
}
```

- **Customtag** (`BeforeDisplay`, `BeforePreview`) en **adsense** (`AfterDisplay`, `AfterPreview`)
  delen één `BBTagReplacer` in plaats van twee kopieën van `replaceTagDeterministic()`.
- **ProtectEmail** wordt een filter op `AfterDisplay`, of verhuist naar `kernel.response` als het voor
  de hele pagina moet gelden.
- `Textsanitizer::displayTarea()` en `DataFilter::filterHTMLdisplay()` roepen
  `$chain->apply(TextStage::…, $text, $ctx)` aan in plaats van `icms::$preload->triggerEvent(...)`.
- De volgorde ligt vast via prioriteiten. Een filter is een gewone service met constructor-injectie,
  dus geen `global $icmsConfigPersona` meer.

#### Templatedata: Twig-globals en lazy functies

`startOutputInit` en `beforeFooter` dienen nu vooral om variabelen en scripts in Smarty te duwen,
vaak met een DB-query bij elke pagina (userinfo, adsense, customtag). Met Twig worden dat globals
(`app.user`, config) en lazy functies (`adsense('tag')`, `customtag('naam')`, `unread_messages()`):
de query loopt alleen als het template de waarde echt gebruikt. Als Smarty blijft, komt er één
getagde `TemplateContextProvider` die de renderer ophaalt.

#### Gevolg voor de BC-brug uit 2.9

De brug wordt kleiner. **Wildcards, annuleren, `current()`/`detach()` en on-demand preloads hoeven er
niet in.** Wat overblijft:

- de `eventXxx()`-conventie en `attach()` voor third-party preloads;
- de oude eventnamen voor tekstfilters, met een `LegacyEvent` waarvan `parameters[0]` een referentie is
  naar de tekst in de keten;
- `loadService-*` zolang derden erop rekenen.

Na deze opkuis blijven er maar een paar echte event-listeners in de core over: protector (in de
module), autotasks (`kernel.terminate` / Scheduler) en het herstarten van autotasks bij een
`ConfigSaved`-domeinevent.

#### Bugs in de huidige preloads

Deze bugs moeten ook zonder Symfony opgelost worden:

- `googleanalytics.php` zet `error getting icmsConfigMetaFooter` in elke pagina zodra GA uit staat.
- `multilanguage.php` leest `$_GET['lang']` in een tak waar alleen de cookie gezet is.
- `protectEmail.php` mist `preg_quote()` en heeft een dode mcrypt/Mailhide-tak.
- `debug_mode.php` luistert naar `prepare`, maar niemand triggert dat event.
- `bootstrap.php` heeft een no-op wildcard-listener.

Los daarvan verdient de autologin-cookie (md5 met het DB-wachtwoord als sleutel) een eigen
security-review, ook los van de DI-migratie.

---

## 3. Migratiepad

Elke fase is los te releasen, BC-neutraal en sluit aan bij de PSR-4-refactor in `REFACTOR_NOTES.md`
(namespaced klassen, `class_alias` voor legacy namen).

| Fase | Inhoud | BC-impact | Afhankelijk van |
|---|---|---|---|
| **DI-0** | `composer.json`: `php: ">=8.4 <9.0"`, `symfony/dependency-injection`, `symfony/config`, `symfony/deprecation-contracts`. **Let op:** `htdocs/composer.json` staat nu op `>=7.4 <8.5` en recente commits (#1772, #1773) herstelden net PHP 7.4-compatibiliteit. Dat moet eerst een expliciete beslissing worden (zie 3.1). | Hosting < 8.4 valt af | — |
| **DI-1** | `Icms\Kernel`: container bouwen, dumpen en cachen. `icms::$services["boot"]` 1-op-1 omzetten naar service-definities met de bestaande factories. `icms::boot()` vult de statische properties vanuit de container; nieuw: `icms::container(): ContainerInterface`. De `loadService`-events blijven vuren. | Geen | DI-0 |
| **DI-1b** | Preloads en events (2.9, 2.10): `symfony/event-dispatcher`; `Icms\Event` wordt een façade over de dispatcher (`LegacyEvent`; geen wildcards, annuleren, `current()`/`detach()` of on-demand preloads); compiler pass die `plugins/preloads/` en modulepreloads scant met `DirectoryResource`; modulemanifest `cache/modules.php`; vaste boot-volgorde in `Kernel::boot()`. Autotasks verhuizen naar `kernel.terminate`. `TextFilterChain` vervangt de tekstfilter-events; de vier `setup()`-preloads verdwijnen naar standaard-Symfony. | Geen voor preloads die de façade gebruiken; de volgorde van preloads wordt deterministisch (alfabetisch) | DI-1 |
| **DI-2** | `HandlerLocator` + `LegacyHandlerFactory`; `icms::handler()` en `icms_getModuleHandler()` delegeren eraan. | Geen (zelfde naamconventies, zelfde caching-semantiek) | DI-1 |
| **DI-3** | Kleine services toevoegen: `RequestContext`, `CurrentUser`, `ModuleContext`, `PermissionService`, `Redirector`, `TemplateProvider`. `Ipf\Permission\Handler` en `Ipf\Registry\Handler` worden dunne wrappers rond die services. | Geen | DI-1 |
| **DI-4** | `HandlerServices` + setter `#[Required]` + lazy fallback in `Ipf\Handler`; `#[AsEntityHandler]` + compiler pass; laden van module-`config/services.php`; cache-invalidatie bij module-install/update. | Geen; opt-in voor modules | DI-2, DI-3 |
| **DI-5** | `ControllerFactory`, `FormFactory`, `TableViewFactory`, `FormElementRegistry`. `Ipf\Entity` gebruikt intern de factories via de handler; `$_POST`/`$_GET` verdwijnen uit Controller en View. | Geen voor modules die de publieke API gebruiken. Modules die `Controller`/`View\Table` rechtstreeks `new`-en blijven werken via optionele constructorparameters met fallback | DI-3, DI-4 |
| **DI-6** | Deprecaties: `trigger_deprecation('impresscms/core', '2.x', …)` bij `icms::handler()`, `icms_getModuleHandler()` en `new Controller(...)` zonder services. Documentatie en een voorbeeldmodule. Optioneel: Rector-regels voor modules. | Alleen notices | DI-5 |
| **DI-7** (volgende major) | Fallbacks verwijderen; `&` uit constructors; `icms::$…`-properties `@internal` of weg; globals weg. | **Breuk** | DI-6 + aankondiging |

**Testen per fase:** de bestaande `tests/Unit/*/AliasesTest.php` blijven groen. Een `ContainerTest`
compileert de container zonder databank (DB als synthetic of lazy service) en controleert dat alle
publieke services resolven. Per geëxtraheerde service komen unit-tests met mocks (zie 2.8).

### 3.1 DI-0: keuze van de PHP-ondergrens

**Advies: PHP 8.4 als ondergrens.** Je wint het meest bij 8.4, en de extra moeite ten opzichte van 8.2
is klein.

#### Levensduur: de grootste winst

| Ondergrens | Security-support PHP tot | Symfony-lijn die je kunt gebruiken |
|---|---|---|
| 8.2 | **31 dec 2026** | 7.4 LTS |
| 8.3 | 31 dec 2027 | 7.4 LTS |
| 8.4 | 31 dec 2028 | 7.4 LTS **én 8.x** (Symfony 8 vraagt PHP 8.4) |

Een ondergrens van 8.2 ligt op het moment van de release al (bijna) buiten support. Met 8.3 koop je één
jaar. Alleen 8.4 laat je de nieuwste Symfony-lijn gebruiken, zodat je niet later een tweede keer moet
migreren. Hetzelfde geldt grotendeels voor PHPUnit: de recentste majors vragen ook 8.3 of 8.4 (het exacte
versienummer per major is nog te controleren). Dat is meteen het moment om `phpunit ^9.6` uit
`htdocs/composer.json` gelijk te trekken met de `^11` uit `REFACTOR_NOTES.md`.

#### Wat 8.3 toevoegt boven 8.2

- **`#[\Override]`**: de belangrijkste voor deze codebase. Er zijn diepe overervingsketens
  (`Core\Entity` → `Ipf\Entity` → module-entity, en `EntityHandler` → `Ipf\Handler` → module-handler).
  `REFACTOR_NOTES.md` vermeldt al LSP-fouten in `initVar()`, `setErrors()` en `fetchObjects()`. Met
  `#[\Override]` faalt een override die zijn parent niet meer raakt luid, in plaats van stil.
- **Getypeerde klasseconstanten** (`const string TYPE = ...`): handig om de `XOBJ_DTYPE_*`-constanten
  om te vormen naar een enum of getypeerde constanten. Die worden nu dubbel en met `if (!defined())`
  gedefinieerd.
- **Dynamisch ophalen van klasseconstanten** (`$class::{$name}`): vervangt `constant("$class::$name")`
  in de code die op naamconventies leunt.
- `json_validate()`, `mb_str_pad()`, en `php -l` op meerdere bestanden tegelijk: dat laatste helpt de
  refactor-scripts.

Functioneel merkt een eindgebruiker hier weinig van. Het is vooral winst in correctheid tijdens de
refactor.

#### Wat 8.4 toevoegt: direct relevant voor IPF en DI

- **Native lazy objects** (`ReflectionClass::newLazyGhost/newLazyProxy`):
  - De Symfony-container kan zware services, zoals DB, sessie en config, lazy maken zonder gegenereerde
    proxyklassen. Vandaag instantieert `icms::boot()` alle boot-services altijd.
  - Hetzelfde mechanisme kan ook **handlers lazy** maken. De `LegacyHandlerFactory` en de fallback via
    `icms::container()` (2.4) worden zo goedkoop.
  - Later kun je zelfs entities lazy hydrateren, bijvoorbeeld voor gerelateerde objecten.
- **Asymmetrische zichtbaarheid** (`public private(set)`). `Ipf\Handler` heeft 20 `public`-properties
  (`$keyName`, `$_moduleName`, `$className`, `$table`, …) die modules lezen, maar die nooit van
  buitenaf gewijzigd mogen worden. Met `public private(set)` blijft het lezen BC-compatibel, en schrijven
  wordt verboden. Dat lukt anders alleen met een BC-breuk via getters.
- **Property hooks**. Op termijn kun je het `getVar()`/`setVar()`/`vars[]`-systeem van `Core\Entity`
  gedeeltelijk vervangen door echte getypeerde properties met hooks voor sanitizing en formattering. Een
  `$entity->title` die de IDE kent, in plaats van `getVar('title', 's')`. Dat is de grootste DX-winst op
  lange termijn, maar wel een grote verbouwing.
- **`#[\Deprecated]`**: precies wat fase DI-6 nodig heeft. `icms::handler()`, `icms_getModuleHandler()`
  en de oude constructors krijgen een deprecatie op engine-niveau, die IDE's en PHPStan ook tonen. Zelf
  `trigger_deprecation()` aanroepen is dan niet meer nodig.
- **`new Foo()->bar()` zonder haakjes, `array_find()`/`array_any()`/`array_all()`, `mb_trim()`**:
  kleine leesbaarheidswinst.
- **Nieuwe HTML5-DOM-API** (`Dom\HTMLDocument`): relevant voor `HTMLFilter` en `Metagen`, die nu met
  regex of de oude DOM-parser werken.

#### Kosten die je sowieso al hebt

Deze kosten heb je ook bij een ondergrens van 8.2, omdat `composer.json` 8.4 vandaag al toelaat:

- **58× `trigger_error(..., E_USER_ERROR)`**: dat is in 8.4 deprecated, onder andere in
  `icms_getModuleHandler()`. Moet hoe dan ook vervangen worden door exceptions.
- **Impliciet nullable parameters** (`Foo $x = null`): deprecated in 8.4. Eén geval in `libraries/Icms`;
  modules kunnen er meer hebben.
- **Dynamische properties** (deprecated sinds 8.2): er staat maar één `#[AllowDynamicProperties]` in de
  codebase. Legacy entity- en module-code die properties aanmaakt zonder ze te declareren, komt hoe dan
  ook boven.

Wat 8.3 of 8.4 als ondergrens *extra* kost, is dus vooral **hosting-compatibiliteit**. Eind 2026 bieden
de gangbare shared hosts 8.3 en 8.4 aan. Hosts die op 8.1 of 8.2 blijven hangen, zitten toch al buiten
security-support.

#### Conclusie

- **8.2** is te kort: het ligt bijna meteen buiten support.
- **8.3** geeft `#[\Override]` en één jaar extra, maar sluit Symfony 8 uit.
- **8.4** geeft lazy objects, asymmetrische zichtbaarheid en `#[\Deprecated]`. Alle drie zijn direct
  bruikbaar in het DI-migratiepad (2.4, DI-6) en voor de publieke properties van `Ipf\Handler`. Je kunt er
  bovendien de Symfony 7.4 LTS **of** de Symfony 8-lijn mee gebruiken.

Daarnaast de bovengrens `<8.5` in `composer.json` loslaten of naar `<9.0` zetten. PHP 8.5 is al uit, en
een harde bovengrens blokkeert gebruikers die nu al upgraden.

---

## 4. Afweging

### 4.1 Technisch

| Voordelen | Nadelen |
|---|---|
| **Testbaarheid.** Permissies, controller-logica en view-sortering worden unit-testbaar zonder databank, sessie of superglobals. Vandaag is `tests/Unit/Ipf` beperkt tot alias-tests, en dat is geen toeval. | **Twee werelden tijdens de overgang.** Elke service heeft een geïnjecteerd en een fallback-pad (`??= icms::container()->get()`). Dat is extra code en extra kans op inconsistentie zolang DI-7 niet gebeurd is. |
| **Expliciete afhankelijkheden.** De constructor vertelt wat een klasse nodig heeft. Nu staan ze verspreid over ±150 plaatsen in `Ipf/` (zie 1.2). | **By-reference-constructors** (22 stuks) vergen een factory-laag (1.3); de container kan ze niet rechtstreeks bouwen. |
| **Vervangbaarheid.** `decorate()` laat modules core-gedrag (bv. permissies) uitbreiden zonder preload-trucs. | **Gecompileerde cache = extra bewegend onderdeel.** Invalidatie bij module-install/update, schrijfrechten op `ICMS_CACHE_PATH`, multi-site-installaties met gedeelde code. Een verouderde container geeft moeilijk te begrijpen fouten. |
| **Performance neutraal tot licht positief.** Een gedumpte container is een gewone PHP-klasse met één methode per service, opcache-vriendelijk. Services worden lazy aangemaakt, terwijl `icms::boot()` nu alle boot-services altijd instantieert. | **Bootstrap-kost bij een koude cache.** `ContainerBuilder` + reflectie + module-scans kost honderden ms. Dat is aanvaardbaar bij install, maar niet als de cache per ongeluk vaak geleegd wordt. |
| **Sluit aan op het ecosysteem.** PSR-11, Symfony-componenten (events, http-foundation, console) worden daarna goedkoop. | **Extra dependencies** (~4 pakketten) en een hogere PHP-ondergrens (8.4 aanbevolen, zie 3.1). Dat botst met de huidige `>=7.4`-belofte. |
| **Bestaand concept.** `icms::$services` + `loadService()` is al een containerpatroon; DI formaliseert het eerder dan het te vervangen. | **Request-scope is lastig.** User, module en request zijn mutable globale toestand die tijdens de boot verandert (`launchModule()` na `boot()`). Providers lossen dat op, maar wie per ongeluk een *waarde* injecteert, krijgt stille bugs. |
| **LSP en strikte types** worden afgedwongen door constructor-handtekeningen. Dat sluit aan bij `declare(strict_types=1)` uit de PSR-4-refactor. | **Entities blijven half.** Ze blijven via `$this->handler` aan services komen (service-locator-light). Echte DI op entities is niet zinvol, maar het resultaat is minder "zuiver" dan in Symfony/Doctrine. |

### 4.2 Developer experience

| Voordelen | Nadelen |
|---|---|
| **IDE en statische analyse.** Getypeerde constructorparameters geven autocompletion en PHPStan-dekking. `\icms::$module->getVar()` op een `public static $module` zonder type geeft vandaag niets. | **Leercurve voor moduleontwikkelaars.** De doelgroep is XOOPS/ImpressCMS-gewend: `icms_getModuleHandler('x')` en klaar. Containers, autowiring, attributen, compiler passes en cache-invalidatie zijn nieuwe concepten. |
| **Minder magie bij handler-lookup.** Nu hangt resolutie af van vier naamconventies en drie bestandslocaties (`include/functions.php:1611-1630`). Met DI: één FQCN of één service-ID. | **Debugging verschuift.** "Waarom krijg ik deze instantie?" vraagt kennis van de gecompileerde container. Zonder `debug:container`-CLI (ImpressCMS heeft geen console) is dat moeilijker. Er moet een admin-pagina of CLI-tool bij. |
| **Herkenbaar voor nieuwe ontwikkelaars** met Symfony- of Laravel-achtergrond; het verlaagt de drempel om bij te dragen aan de core. | **Meer boilerplate** voor kleine modules: `services.php`, attribuut, constructor met `HandlerServices`. Het oude pad blijft korter, waardoor de verleiding groot is om het te blijven gebruiken. |
| **Modules kunnen eigen services aanbieden** en die van andere modules gebruiken (bv. een "tags"-module) zonder `icms_getModuleHandler('tag', 'tags')` en hopen dat ze geïnstalleerd is. | **Documentatie en voorbeelden ontbreken** en moeten geschreven worden. De bestaande module-tutorials worden gedeeltelijk verouderd. |
| **Geen BC-breuk** in de voorgestelde fasering. Bestaande third-party modules blijven ongewijzigd werken tot een aangekondigde major. | **Deprecation-ruis.** Vanaf DI-6 zien bestaande modules notices in de debug-logger. Dat is goed bedoeld, maar zonder Rector- of migratiehulp frustreert het. |
| **Testbare modules.** Moduleontwikkelaars krijgen voor het eerst een realistische manier om hun handlers te unit-testen. | **Twee manieren om hetzelfde te doen** gedurende minstens één major-cyclus. Code reviews en documentatie moeten steeds beide uitleggen. |

### 4.3 Samenvatting

| Aspect | Volledige DI | Gedeeltelijke DI (aanbevolen) | Niet doen |
|---|---|---|---|
| Testbaarheid IPF | ++ | + | −− |
| BC-risico | −− (breuk nodig) | 0 (fasen DI-0..6) | 0 |
| Werk (core) | groot | middel | geen |
| Werk (modules) | verplicht | opt-in | geen |
| Leercurve | hoog | laag tot middel | geen |
| Performance | 0/+ | 0/+ | 0 |
| PHP-eis | 8.4+ | 8.4+ | ongewijzigd |
| Toekomstvastheid | ++ | + | − |

---

## 5. Aanbeveling

**Gedeeltelijke DI, in fasen DI-0 t.e.m. DI-6. Volledige DI (DI-7) pas in een volgende major.**

Concreet:

1. **Wel een service:** infrastructuur, handlers (via `HandlerLocator` en factories), de geëxtraheerde
   IPF-services (`PermissionService`, `RequestContext`, `CurrentUser`, `ModuleContext`, …) en de
   factories voor Controller, Form en View.
2. **Geen service:** entities (de handler is hun enige afhankelijkheid), form-elementen (die komen via een
   registry), pure filterfuncties.
3. **Behoud** `icms::$…` en `icms_getModuleHandler()` als façade bovenop de container. Voor
   moduleontwikkelaars verandert er niets tot ze zelf kiezen voor `services.php` en constructor-injectie.
4. **De grootste winst per uur werk** zit in DI-3 en DI-5: `Controller` en `View\Table` loskoppelen van
   superglobals en `Permission\Handler` van `icms::$user`. Dat is meteen testbare code. Begin daar, na de
   minimale container uit DI-1.
5. **Eerst beslissen:** de PHP-ondergrens. Advies: 8.4 (zie 3.1), terwijl `composer.json` nu `>=7.4`
   belooft. Zonder die beslissing is DI-0 niet uitvoerbaar. Een alternatief is Symfony DI 5.4
   (PHP 7.2+, maar end-of-life), en dat wordt afgeraden.

Niet doen is verdedigbaar voor een project in onderhoudsmodus. Maar de PSR-4-refactor
(`REFACTOR_NOTES.md`) heeft al de moeilijkste stap gezet (namespaces, strikte types, aliassen). DI is
de logische volgende stap om die investering ook in testbaarheid en uitbreidbaarheid te laten renderen.
