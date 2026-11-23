# Dependency Injection voor Core en IPF — analyse en voorstel

**Status:** voorstel, nog geen code gewijzigd
**Uitgangspunten:** PHP 8.4, `symfony/dependency-injection` (7.x) als container, BC blijft een harde eis
**Scope:** `htdocs/libraries/Icms/Core/` (vooral `Entity`, `EntityHandler`), `htdocs/libraries/Icms/Ipf/` (Entity, Handler, Controller, subhandlers, Form, View) en de handler-loaders `icms::handler()` en `icms_getModuleHandler()`.

---

## 1. Inventaris: hoe komen klassen nu aan hun afhankelijkheden?

### 1.1 De vijf mechanismen

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

---

## 3. Migratiepad

Elke fase is los te releasen, BC-neutraal en sluit aan bij de PSR-4-refactor in `REFACTOR_NOTES.md`
(namespaced klassen, `class_alias` voor legacy namen).

| Fase | Inhoud | BC-impact | Afhankelijk van |
|---|---|---|---|
| **DI-0** | `composer.json`: `php: ">=8.2"`, `symfony/dependency-injection`, `symfony/config`, `symfony/deprecation-contracts`. **Let op:** `htdocs/composer.json` staat nu op `>=7.4 <8.5` en recente commits (#1772, #1773) herstelden net PHP 7.4-compatibiliteit. Dat moet eerst een expliciete beslissing worden. | Hosting < 8.2 valt af | — |
| **DI-1** | `Icms\Kernel`: container bouwen, dumpen en cachen. `icms::$services["boot"]` 1-op-1 omzetten naar service-definities met de bestaande factories. `icms::boot()` vult de statische properties vanuit de container; nieuw: `icms::container(): ContainerInterface`. De `loadService`-events blijven vuren. | Geen | DI-0 |
| **DI-2** | `HandlerLocator` + `LegacyHandlerFactory`; `icms::handler()` en `icms_getModuleHandler()` delegeren eraan. | Geen (zelfde naamconventies, zelfde caching-semantiek) | DI-1 |
| **DI-3** | Kleine services toevoegen: `RequestContext`, `CurrentUser`, `ModuleContext`, `PermissionService`, `Redirector`, `TemplateProvider`. `Ipf\Permission\Handler` en `Ipf\Registry\Handler` worden dunne wrappers rond die services. | Geen | DI-1 |
| **DI-4** | `HandlerServices` + setter `#[Required]` + lazy fallback in `Ipf\Handler`; `#[AsEntityHandler]` + compiler pass; laden van module-`config/services.php`; cache-invalidatie bij module-install/update. | Geen; opt-in voor modules | DI-2, DI-3 |
| **DI-5** | `ControllerFactory`, `FormFactory`, `TableViewFactory`, `FormElementRegistry`. `Ipf\Entity` gebruikt intern de factories via de handler; `$_POST`/`$_GET` verdwijnen uit Controller en View. | Geen voor modules die de publieke API gebruiken. Modules die `Controller`/`View\Table` rechtstreeks `new`-en blijven werken via optionele constructorparameters met fallback | DI-3, DI-4 |
| **DI-6** | Deprecaties: `trigger_deprecation('impresscms/core', '2.x', …)` bij `icms::handler()`, `icms_getModuleHandler()` en `new Controller(...)` zonder services. Documentatie en een voorbeeldmodule. Optioneel: Rector-regels voor modules. | Alleen notices | DI-5 |
| **DI-7** (volgende major) | Fallbacks verwijderen; `&` uit constructors; `icms::$…`-properties `@internal` of weg; globals weg. | **Breuk** | DI-6 + aankondiging |

**Testen per fase:** de bestaande `tests/Unit/*/AliasesTest.php` blijven groen. Een `ContainerTest`
compileert de container zonder databank (DB als synthetic of lazy service) en controleert dat alle
publieke services resolven. Per geëxtraheerde service komen unit-tests met mocks (zie 2.8).

---

## 4. Afweging

### 4.1 Technisch

| Voordelen | Nadelen |
|---|---|
| **Testbaarheid.** Permissies, controller-logica en view-sortering worden unit-testbaar zonder databank, sessie of superglobals. Vandaag is `tests/Unit/Ipf` beperkt tot alias-tests, en dat is geen toeval. | **Twee werelden tijdens de overgang.** Elke service heeft een geïnjecteerd en een fallback-pad (`??= icms::container()->get()`). Dat is extra code en extra kans op inconsistentie zolang DI-7 niet gebeurd is. |
| **Expliciete afhankelijkheden.** De constructor vertelt wat een klasse nodig heeft. Nu staan ze verspreid over ±150 plaatsen in `Ipf/` (zie 1.2). | **By-reference-constructors** (22 stuks) vergen een factory-laag (1.3); de container kan ze niet rechtstreeks bouwen. |
| **Vervangbaarheid.** `decorate()` laat modules core-gedrag (bv. permissies) uitbreiden zonder preload-trucs. | **Gecompileerde cache = extra bewegend onderdeel.** Invalidatie bij module-install/update, schrijfrechten op `ICMS_CACHE_PATH`, multi-site-installaties met gedeelde code. Een verouderde container geeft moeilijk te begrijpen fouten. |
| **Performance neutraal tot licht positief.** Een gedumpte container is een gewone PHP-klasse met één methode per service, opcache-vriendelijk. Services worden lazy aangemaakt, terwijl `icms::boot()` nu alle boot-services altijd instantieert. | **Bootstrap-kost bij een koude cache.** `ContainerBuilder` + reflectie + module-scans kost honderden ms. Dat is aanvaardbaar bij install, maar niet als de cache per ongeluk vaak geleegd wordt. |
| **Sluit aan op het ecosysteem.** PSR-11, Symfony-componenten (events, http-foundation, console) worden daarna goedkoop. | **Extra dependencies** (~4 pakketten) en de PHP 8.2+-eis van Symfony 7. Dat botst met de huidige `>=7.4`-belofte. |
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
| PHP-eis | 8.2+ | 8.2+ | ongewijzigd |
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
5. **Eerst beslissen:** de PHP-ondergrens. Symfony DI 7 vraagt PHP 8.2+, terwijl `composer.json` nu
   `>=7.4` belooft. Zonder die beslissing is DI-0 niet uitvoerbaar. Een alternatief is Symfony DI 5.4
   (PHP 7.2+, maar end-of-life), en dat wordt afgeraden.

Niet doen is verdedigbaar voor een project in onderhoudsmodus. Maar de PSR-4-refactor
(`REFACTOR_NOTES.md`) heeft al de moeilijkste stap gezet (namespaces, strikte types, aliassen). DI is
de logische volgende stap om die investering ook in testbaarheid en uitbreidbaarheid te laten renderen.
