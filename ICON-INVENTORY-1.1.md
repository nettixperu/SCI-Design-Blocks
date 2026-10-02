# SCI Design Blocks — Icon Inventory 1.1

**Estado:** M2 implementado en la rama de desarrollo; catálogo curado final documentado.
**Core inspeccionado:** WordPress 7.1.2 local, versión mínima de trabajo 7.1.
**Conceptos evaluados:** 183 filas conceptuales; **sin icono SCI por equivalente Core GOOD/ACCEPTABLE:** 67.
**Biblioteca externa:** Bootstrap Icons v1.13.1; nombres Core proceden del manifiesto de 88 iconos inspeccionado en WordPress 7.1.2.

Quality expresa la adecuación del glifo Core a ese concepto concreto. GOOD/ACCEPTABLE se deduplica y queda sin icono SCI. POOR/MISSING se evaluó para la selección; la tabla final más abajo registra el catálogo efectivamente incluido.

## Infrastructure & Cloud

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| server | — | MISSING | Yes — `hdd` |
| server rack | — | MISSING | Yes — `hdd-rack` |
| datacenter | — | MISSING | Yes — `buildings` |
| cloud | — | MISSING | Yes — `cloud` |
| private cloud | — | MISSING | Yes — `cloud + lock` |
| database | — | MISSING | Yes — `database` |
| storage | `file` | POOR | Yes — `hdd` |
| hard drive | — | MISSING | Yes — `hdd` |
| NAS | — | MISSING | Yes — `device-hdd` |
| CPU | — | MISSING | Yes — `cpu` |
| memory | — | MISSING | Yes — `memory` |
| virtual machine | `desktop` | POOR | Yes — `pc-display` |
| container | `group` | POOR | Yes — `boxes` |
| network | `share` | POOR | Yes — `diagram-3` |
| ethernet | — | MISSING | Yes — `ethernet` |
| router | — | MISSING | Yes — `router` |
| monitoring | `chart-bar` | ACCEPTABLE | No — Core `chart-bar` |
| high availability | `check` | POOR | Yes — `cloud + check` |
| cluster | `group` | POOR | Yes — `diagram-3` |

## Security

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| shield | `shield` | GOOD | No — Core `shield` |
| shield check | `shield` | ACCEPTABLE | No — Core `shield` |
| lock | — | MISSING | Yes — `lock` |
| unlock | — | MISSING | Yes — `unlock` |
| key | `key` | GOOD | No — Core `key` |
| fingerprint | — | MISSING | Yes — `fingerprint` |
| firewall | `shield` | POOR | Yes — `shield-lock` |
| VPN | — | MISSING | Yes — `shield-lock` |
| threat | `caution` | ACCEPTABLE | No — Core `caution` |
| malware | — | MISSING | Yes — `bug` |
| bug | — | MISSING | Yes — `bug` |
| scan | `search` | ACCEPTABLE | No — Core `search` |
| certificate | `published` | POOR | Yes — `patch-check` |
| secure connection | `shield` | ACCEPTABLE | No — Core `shield` |
| antispam | — | MISSING | Yes — `envelope-slash` |
| warning | `caution` | GOOD | No — Core `caution` |
| monitoring/security | `shield` | ACCEPTABLE | No — Core `shield` |

## Web & Communication

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| mail | `envelope` | GOOD | No — Core `envelope` |
| inbox | — | MISSING | Yes — `inbox` |
| send | — | MISSING | Yes — `send` |
| mail check | `envelope` | POOR | Yes — `envelope-check` |
| mail warning | `envelope` | POOR | Yes — `envelope-exclamation` |
| spam | — | MISSING | Yes — `envelope-slash` |
| message | `comment` | GOOD | No — Core `comment` |
| messages | `comment` | ACCEPTABLE | No — Core `comment`; `chat-dots` excluded as duplicate |
| phone | — | MISSING | Yes — `telephone` |
| headset | — | MISSING | Yes — `headset` |
| bell | `bell` | GOOD | No — Core `bell` |
| globe | `language` | ACCEPTABLE | No — Core `language` |
| browser | `desktop` | POOR | Yes — `window-desktop` |
| code | — | MISSING | Yes — `code-slash` |
| terminal | — | MISSING | Yes — `terminal` |
| domain | — | MISSING | Yes — `globe2` |
| DNS | — | MISSING | Yes — `globe2` |
| API | — | MISSING | Yes — `braces` |
| link | `external` | ACCEPTABLE | No — Core `external`; `link-45deg` excluded as duplicate |
| external link | `external` | GOOD | No — Core `external` |
| hosting | — | MISSING | Yes — `hdd-rack` |

## Files & Backup

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| file | `file` | GOOD | No — Core `file` |
| files | `file` | ACCEPTABLE | No — Core `file` |
| folder | — | MISSING | Yes — `folder` |
| archive | — | MISSING | Yes — `archive` |
| backup | — | MISSING | Yes — `cloud-arrow-up` |
| restore | — | MISSING | Yes — `arrow-counterclockwise` |
| sync | `shuffle` | POOR | Yes — `arrow-repeat` |
| upload | `upload` | GOOD | No — Core `upload` |
| download | `download` | GOOD | No — Core `download` |
| copy | — | MISSING | Yes — `copy` |
| history | `scheduled` | POOR | Yes — `clock-history` |
| object storage | — | MISSING | Yes — `cloud-arrow-up` |

## Business & SMEs

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| company | `people` | POOR | Yes — `building` |
| building | — | MISSING | Yes — `building` |
| store | `store` | GOOD | No — Core `store` |
| SME/business | `store` | ACCEPTABLE | No — Core `store` |
| briefcase | — | MISSING | Yes — `briefcase` |
| handshake | — | MISSING | Yes — `person-plus` |
| partner | `people` | ACCEPTABLE | No — Core `people` |
| supplier | — | MISSING | Yes — `truck` |
| customer | `people` | ACCEPTABLE | No — Core `people` |
| target | — | MISSING | Yes — `bullseye` |
| opportunity | `star-empty` | POOR | Yes — `rocket-takeoff` |
| rocket | — | MISSING | Yes — `rocket-takeoff` |
| lightbulb | `tip` | ACCEPTABLE | No — Core `tip`; `lightbulb` excluded from final SCI subset |
| growth | `chart-bar` | ACCEPTABLE | No — Core `chart-bar`; `graph-up-arrow` excluded from final SCI subset |
| cost savings | `payment` | POOR | Yes — `piggy-bank` |
| productivity | — | MISSING | Yes — `speedometer2` |
| consulting | `people` | POOR | Yes — `person-workspace` |
| contract | `file` | POOR | Yes — `file-earmark-text` |
| quotation | `quote` | ACCEPTABLE | No — Core `quote`; `chat-quote` excluded as duplicate |
| invoice | `receipt` | ACCEPTABLE | No — Core `receipt` |
| receipt | `receipt` | GOOD | No — Core `receipt` |
| subscription | `payment` | POOR | Yes — `calendar-check` |
| wallet | — | MISSING | Yes — `wallet2` |
| payment | `payment` | GOOD | No — Core `payment` |
| credit card | — | MISSING | Yes — `credit-card` |
| bank | — | MISSING | Yes — `bank` |
| calculator | — | MISSING | Yes — `calculator` |
| ROI | `chart-bar` | POOR | Yes — `cash-coin` |

## Sales & Marketing

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| megaphone | — | MISSING | Yes — `megaphone` |
| funnel | — | MISSING | Yes — `funnel` |
| lead | `people` | POOR | Yes — `person-lines-fill` |
| cart | `cart` | GOOD | No — Core `cart` |
| tag | `tag` | GOOD | No — Core `tag` |
| percentage | — | MISSING | Yes — `percent` |
| chart up | `chart-bar` | ACCEPTABLE | No — Core `chart-bar` |
| analytics | `chart-bar` | ACCEPTABLE | No — Core `chart-bar` |
| presentation | — | MISSING | Yes — `easel` |
| campaign | `calendar` | POOR | Yes — `megaphone` |
| conversion | — | MISSING | Yes — `funnel` |
| audience | `people` | ACCEPTABLE | No — Core `people` |
| target | — | MISSING | Yes — `bullseye` |
| marketing | — | MISSING | Yes — `megaphone` |
| sales | `cart` | ACCEPTABLE | No — Core `cart` |

## People & Productivity

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| user | `people` | ACCEPTABLE | No — Core `people` |
| users | `people` | GOOD | No — Core `people` |
| team | `people` | ACCEPTABLE | No — Core `people` |
| user plus | `people` | POOR | Yes — `person-plus` |
| contact | `people` | ACCEPTABLE | No — Core `people` |
| ID | — | MISSING | Yes — `person-vcard` |
| support agent | `help` | POOR | Yes — `headset` |
| meeting | `calendar` | POOR | Yes — `calendar-event` |
| collaboration | `group` | ACCEPTABLE | No — Core `group` |
| calendar | `calendar` | GOOD | No — Core `calendar` |
| clock | — | MISSING | Yes — `clock` |
| checklist | `check` | ACCEPTABLE | No — Core `check`; `list-check` excluded as duplicate |
| task | `check` | ACCEPTABLE | No — Core `check` |
| clipboard | — | MISSING | Yes — `clipboard-check` |
| document | `file` | GOOD | No — Core `file` |
| workflow | — | MISSING | Yes — `diagram-2` |
| automation | `settings` | POOR | Yes — `gear-wide-connected` |
| integration | `external` | POOR | Yes — `puzzle` |

## Community & Events

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| community | `people` | ACCEPTABLE | No — Core `people` |
| members | `people` | GOOD | No — Core `people` |
| networking | `people` | ACCEPTABLE | No — Core `people` |
| connection | `share` | ACCEPTABLE | No — Core `share` |
| speaker | — | MISSING | Yes — `mic` |
| talk | `comment` | ACCEPTABLE | No — Core `comment` |
| microphone | — | MISSING | Yes — `mic` |
| live | — | MISSING | Yes — `broadcast` |
| broadcast | `rss` | POOR | Yes — `broadcast` |
| event | `calendar` | ACCEPTABLE | No — Core `calendar` |
| calendar | `calendar` | GOOD | No — Core `calendar` |
| ticket | — | MISSING | Yes — `ticket-perforated` |
| badge | `tag` | POOR | Yes — `ticket-perforated` |
| award | `star-filled` | ACCEPTABLE | No — Core `star-filled` |
| workshop | — | MISSING | Yes — `tools` |
| education | — | MISSING | Yes — `mortarboard` |
| presentation | — | MISSING | Yes — `easel` |
| video live | `capture-video` | POOR | Yes — `broadcast` |
| map pin | `map-marker` | GOOD | No — Core `map-marker` |
| partner | `people` | ACCEPTABLE | No — Core `people` |
| sponsor | — | MISSING | Yes — `briefcase` |
| collaboration | `group` | ACCEPTABLE | No — Core `group` |

## Photography & Multimedia

| Concept | Core equivalent | Quality | SCI icon needed? |
| --- | --- | --- | --- |
| camera | `capture-photo` | GOOD | No — Core `capture-photo` |
| lens | — | MISSING | Yes — `crosshair` |
| aperture | — | MISSING | Yes — `crosshair` |
| shutter | — | MISSING | Yes — `sliders` |
| focus | — | MISSING | Yes — `crosshair` |
| autofocus | — | MISSING | Yes — `crosshair` |
| flash | — | MISSING | Yes — `lightning` |
| exposure | — | MISSING | Yes — `brightness-high` |
| ISO | — | MISSING | Yes — `sliders` |
| film roll | — | MISSING | Yes — `film` |
| photo | `image` | GOOD | No — Core `image` |
| images | `gallery` | GOOD | No — Core `gallery` |
| gallery | `gallery` | GOOD | No — Core `gallery` |
| album | `gallery` | ACCEPTABLE | No — Core `gallery` |
| collection | `gallery` | ACCEPTABLE | No — Core `gallery` |
| RAW | — | MISSING | Yes — `filetype-raw` |
| EXIF / metadata | `info` | POOR | DEFER — explicitly out of M2; no dedicated icon |
| crop | — | MISSING | Yes — `crop` |
| edit | `pencil` | GOOD | No — Core `pencil` |
| sliders | `settings` | POOR | Yes — `sliders` |
| histogram | `chart-bar` | POOR | Yes — `activity` |
| favorite | `star-filled` | GOOD | No — Core `star-filled` |
| location | `map-marker` | GOOD | No — Core `map-marker` |
| drone | — | MISSING | DEFER — GAP-A, no concrete general pattern/use case |
| action camera | `capture-video` | POOR | Yes — `camera-reels` |
| smartphone camera | `mobile` | POOR | Yes — `camera-reels` |
| video | `capture-video` | GOOD | No — Core `capture-video` |
| microphone | — | MISSING | Yes — `mic` |
| headphones | — | MISSING | Yes — `headphones` |
| play | `audio` | POOR | Yes — `play-fill` |
| monitor | `desktop` | ACCEPTABLE | No — Core `desktop` |

## Final M2 catalog and dispositions

- **GAP-A:** `drone` remains deferred; no broad editorial use case justifies it.
- **EXIF / metadata:** explicitly deferred; no dedicated or custom icon is included.
- **Core-equivalent proposal exclusions:** six handles removed after checking the Core inventory: `chat-dots` (Core `comment`), `link-45deg` (Core `external`), `lightbulb` (Core `tip`), `chat-quote` (Core `quote`), `graph-up-arrow` (Core `chart-bar`), and `list-check` (Core `check`).
- **Brand icons:** deferred. No brand marks are included.

The source proposal contained 89 distinct icons (60,545 raw source bytes). The final catalog contains **83 distinct, unmodified Bootstrap Icons v1.13.1 files**, totaling **56,805 raw SVG bytes**. The catalog is opt-in through ordinary `core/icon`; existing patterns do not default to SCI icons.

| Category | Final upstream icon names | Count |
| --- | --- | ---: |
| Infrastructure & Cloud | `hdd-rack`, `buildings`, `cloud`, `database`, `hdd`, `device-hdd`, `cpu`, `memory`, `pc-display`, `boxes`, `diagram-3`, `ethernet`, `router`, `activity` | 14 |
| Security | `shield-lock`, `lock`, `unlock`, `fingerprint`, `bug`, `patch-check`, `envelope-slash` | 7 |
| Web & Communication | `inbox`, `send`, `envelope-check`, `envelope-exclamation`, `telephone`, `headset`, `globe2`, `window-desktop`, `code-slash`, `terminal`, `braces` | 11 |
| Files & Backup | `folder`, `archive`, `cloud-arrow-up`, `arrow-counterclockwise`, `arrow-repeat`, `copy`, `clock-history` | 7 |
| Business & SMEs | `building`, `briefcase`, `person-plus`, `truck`, `bullseye`, `rocket-takeoff`, `piggy-bank`, `speedometer2`, `person-workspace`, `file-earmark-text`, `calendar-check`, `wallet2`, `credit-card`, `bank`, `calculator`, `cash-coin` | 16 |
| Sales & Marketing | `megaphone`, `funnel`, `person-lines-fill`, `percent`, `easel`, `mouse` | 6 |
| People & Productivity | `person-vcard`, `calendar-event`, `clock`, `clipboard-check`, `diagram-2`, `gear-wide-connected`, `puzzle` | 7 |
| Community & Events | `mic`, `broadcast`, `ticket-perforated`, `mortarboard`, `tools` | 5 |
| Photography & Multimedia | `crosshair`, `lightning`, `brightness-high`, `sliders`, `film`, `filetype-raw`, `crop`, `camera-reels`, `headphones`, `play-fill` | 10 |
| **Total** |  | **83** |

Labels are concise, translation-ready names selected to help Core’s name/label search. No keyword aliases or duplicate handles are created.

## Reproducibility evidence

- Core inventory source: `wp-includes/assets/icon-library-manifest.php` and `wp-includes/images/icon-library/` in the local WordPress 7.1.2 tree; 88 manifest entries.
- Proposed external icon names/bytes: official Bootstrap Icons repository tree at tag `v1.13.1`; each name was checked against that pinned tree.
- Full registration, Core API, sanitization, picker search and runtime behavior are documented in [ICON-LIBRARY-SPIKE-1.1.md](ICON-LIBRARY-SPIKE-1.1.md).
