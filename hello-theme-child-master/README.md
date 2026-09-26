# NYC Icon Limo — Hello Elementor Child Theme

The header and footer from the original HTML build, rebuilt as WordPress
templates. Nothing is hardcoded into a page: menus come from Appearance →
Menus, and every piece of text comes from the Customizer.

## Install

1. Upload the folder to `/wp-content/themes/` (or upload the zip under
   Appearance → Themes → Add New).
2. Make sure the **Hello Elementor** parent theme is installed. It does not
   need to be activated — only present.
3. Activate **Hello Elementor Child**.

## First-run setup — about ten minutes

### 1. Menus

Five locations are registered. Create your menus under **Appearance → Menus**,
then assign one to each location.

You can assign them from either of two places — they are the same setting, so
it makes no difference which you use:

- **Customizer → NYC Icon Limo** — each section has the picker for its own
  menu. The footer column pickers sit right above the column headings.
- **Appearance → Menus → Manage Locations** — the standard WordPress screen.

| Location | Where it appears |
| --- | --- |
| Primary Menu (header) | The desktop navigation bar |
| Mobile Drawer Menu | The full-screen menu below 1100px |
| Footer Column 1 | Under the first footer heading (default "Company") |
| Footer Column 2 | Under the second footer heading (default "Services") |
| Footer Legal Links | The small print row at the very bottom |

An unassigned location renders nothing for visitors — the layout stays
intact. Logged-in administrators see an "Assign a menu" link instead.

**The Services dropdown.** Any top-level item in the Primary Menu that has
children becomes a dropdown panel automatically. Its child items each get:

- an **Icon**, picked from the select on the menu item itself;
- a **subtitle**, taken from the menu item's **Description** field.

Both fields are on the menu item once you expand it. Description is shown by
default; if you ever hide it under Screen Options you can turn it back on
there.

The Primary Menu only renders two levels deep. Anything nested deeper is
flattened into the same panel rather than creating a third level.

### 2. Logo (Customizer → Site Identity)

Upload a **Site Logo** and it replaces the monument mark in both the header
and the footer. With no logo set, the original inline SVG mark plus the
wordmark is used, so the header is never empty.

### 3. Everything else (Customizer → NYC Icon Limo)

| Section | Controls |
| --- | --- |
| Contact Details | Phone (displayed and dialled separately), reservations email |
| Top Bar | Show/hide, badge text, service area line, show/hide email and phone |
| Header | Brand name and strapline, phone label, button text and link |
| Mobile Menu Drawer | Heading, button, small print |
| Footer: Brand & Social | Intro paragraph, seven social URLs |
| Footer: Column Headings | The three column headings |
| Footer: Reservations Column | Notes beside the phone and email, service area, button |
| Footer: Bottom Bar | Copyright line |
| Mobile Action Bar | Show/hide, Call and Book button text and link |

Two things worth knowing:

- **Phone number is entered twice on purpose.** "As displayed" is what people
  read, e.g. `(917) 952-4031`. "For dialling" is what the phone actually
  dials, e.g. `+19179524031`. It is used everywhere a phone number appears.
- **The copyright line takes `%year%`**, which is replaced with the current
  year at render time. It updates itself every January.

A social URL left empty means that icon is not rendered at all — there are no
placeholder links pointing at `#`.

## Fleet

A **Fleet** menu appears in the dashboard. Each vehicle is a post with its own
photo, capacities and copy, and a `[fleet]` shortcode renders them.

### The demo fleet is already there

The six vehicles from the original site — names, models, descriptions,
capacities, and their photos copied into the Media Library as featured images
— import themselves the first time you open the dashboard after activating the
theme. Nothing to click: open **Fleet** and they are waiting.

It runs **once, ever**, and bails the moment it finds a vehicle already there,
so it can never overwrite work or duplicate anything.

If it did not happen, or you deleted them and want them back:
**Fleet → Import Original Fleet → Import**. Safe to run twice — a vehicle that
already exists is skipped.

### Seeing it before you build a page

**Fleet → Preview.** The real `[fleet]` output with your live vehicles and the
site's own stylesheets, in an iframe you can flip between desktop, tablet and
mobile widths. The filter tabs work in there too.

Change a vehicle, reload, see it — no page needed, nothing published.

### Managing vehicles

| Field | Where |
| --- | --- |
| Name | The post title |
| Photo | Featured Image |
| Type (drives the filter tabs) | Vehicle Types box — add your own types freely |
| Class badge, model line, description | Vehicle Details box |
| Passengers, bags | Vehicle Details box |
| Extra features | Vehicle Details box, one per line |
| Price, price label, button text and link | Vehicle Details box |
| Card order | Order under Page Attributes, lowest first |

**Extra features** take an optional icon: write `shield:Insured` or
`clock:24/7` to pick one. Available icons are shield, clock, users, car,
plane, star, pin and calendar. A line with no prefix gets the shield.

**Filter tabs** build themselves from the Vehicle Types that actually have a
vehicle in them, and only appear when there is more than one. Add, rename or
delete a type and the tabs follow.

### Placing it with Elementor

Drop a **Shortcode** widget wherever you want the fleet and use:

```
[fleet]
```

| Attribute | Effect |
| --- | --- |
| `filter="no"` | Hide the type tabs |
| `columns="2"` | Cards per row, 1–4. Default 3 |
| `type="sedans"` | One type only — use the type slug, comma-separate several |
| `limit="3"` | Show only the first few |
| `heading="yes"` | Add an eyebrow and title above the grid |
| `book_url="/contact/#book"` | Where Reserve points. The vehicle name is appended automatically |

The shortcode outputs the tabs and the card grid, and nothing else — no
section padding, no width wrapper, no background. Those belong to the
Elementor container it sits in, so it cannot fight your page layout.

`heading` is off by default for the same reason: your Elementor heading widget
stays in charge unless you ask for one.

### It stays out of the way

The Vehicles post type is registered with `public => false` — no single-vehicle
URLs, no archive, nothing added to the site's permalink structure, so it cannot
collide with a page you build in Elementor. It renders only inside its own
shortcode, and enqueues nothing: the cards are styled by `components.css`,
which is already loaded.

## Services and Events

Two separate menus in the dashboard, **Services** and **Events**, each with its
own list, its own demo import and its own shortcode. They draw the same tile,
so the markup and the fields are shared in code — but nothing is mixed
together on screen.

```
[services]      the grid on the Services page
[events]        the Occasions grid on the Events page
```

| Attribute | Effect |
| --- | --- |
| `columns="2"` | Tiles per row, 1–4. Default 3 |
| `limit="3"` | Show only the first few |
| `heading="yes"` | Add the eyebrow and title above the grid |

### The demo tiles are already there

Six under Services, six under Events, photos and all. They import themselves
on the first dashboard load, the same way the Fleet does — once, ever, and
never touching a tile you have already made.

Re-run either from **Import Demo Content**, in the menu for that type.

### Managing tiles

| Field | Where |
| --- | --- |
| Title | The post title |
| Background photo | Featured Image |
| Label (the gold pill) | Tile box |
| Description | Tile box |
| Link text and URL | Tile box |
| Double width | Tile box — spans two columns, as Airport Transportation did |
| Order | Order under Page Attributes, lowest first |

**Preview** sits under each menu and renders that type's grid with your live
tiles, at desktop, tablet and mobile widths.

Like the Fleet, each shortcode outputs the grid and nothing else — no section
padding, no width wrapper, no background. Those belong to the Elementor
container.

**One thing to fix after importing:** the seeded link URLs are the original
paths rewritten as WordPress permalinks — `/airport-transfers/`,
`/corporate-travel/`, `/contact/#book`. They will not resolve until you create
pages at those slugs, so check them against your real pages.

### Upgrading from an earlier version

Before this version both grids lived in one Services list, separated by a
Group taxonomy — which is exactly the confusion this split removes. Anything
already filed under the Events group is moved to the Events type on the first
dashboard load, keeping its content, photo and order. Nothing is recreated and
nothing is lost, and the old group is cleared away afterwards.

## How it fits together

```
header.php                  Top bar, nav, mobile drawer
footer.php                  Footer, mobile action bar
functions.php               Menu locations, theme supports, asset loading
style.css                   WordPress-only fixes (admin bar, logo sizing)
inc/
  customizer.php            The NYC Icon Limo panel
  template-tags.php         Option defaults + brand, social, link helpers
  icons.php                 Inline SVG library + the menu item icon picker
  class-nyc-nav-walker.php  Builds the header dropdown markup
  class-nyc-flat-walker.php Builds flat link lists (drawer, footer columns)
  fleet.php                 Vehicles post type, fields, [fleet] shortcode
  fleet-import.php          One-click import of the original six vehicles
  tiles.php                 Services + Events post types, [services] and [events]
  tiles-import.php          Demo import, preview and the one-time split migration
assets/css/                 The original project stylesheets, unchanged
assets/js/                  The original project scripts, unchanged
```

### Defaults live in one place

`nyc_defaults()` in `inc/template-tags.php` holds the default for every
setting. The Customizer controls and the templates both read it, so they
cannot drift apart. A fresh install renders exactly like the original HTML
before anything is edited.

### Elementor

Both templates check for an Elementor Theme Builder header or footer first.
If you publish one, it takes over and these step aside — so you can move to an
Elementor-built header later without touching this code.

For page content, set **Content Width to 1300px** under Elementor → Site
Settings → Layout to match the design.

**Elementor Theme Style outranks the chrome.** Elementor writes its Theme
Style typography as `.elementor-kit-123 h2 { … }`. Because the kit class sits
on `<body>`, that selector is more specific than the project's own
`.footer__heading`, and Elementor wins — footer headings render at full H2
display size instead of 16px.

This never shows up when testing the HTML locally, because there is no
Elementor there. Section 4 of `style.css` fixes it by repeating the chrome's
values behind a second class. If you later set Theme Style values for
paragraphs or links and something in the header or footer shifts, extend that
block the same way. Specificity, not `!important`.

### Header position

The header is **sticky**: `position: sticky; top: 0`.

Unlike `fixed`, a sticky element stays in the document flow and reserves its
own height, so nothing built in Elementor can end up behind it at rest. It
only rides over the content while you scroll, which is the point.

The original static build had it `position: fixed`, because every page there
opened with a full-bleed hero photo designed to run underneath it. That is
the wrong default for pages designed in Elementor — a fixed header reserves
no space, so widgets slide under it.

For a header that scrolls away with the page instead, set `position: static`
in section 1 of `style.css` and drop the two admin-bar rules in section 5.

Two things sticky depends on, both handled in `style.css`:

- **`body { overflow-x: clip }`** — sticky is silently disabled by any scroll
  container between the element and the viewport, and `base.css` sets
  `overflow-x: hidden` on body, which makes body exactly that. `clip` hides
  the same overflow without creating one.
- **The admin bar offset** — a sticky header would otherwise come to rest
  underneath it.

The header renders solid (white bar, dark text) because there is no hero
photo behind it to be transparent over. Its drop shadow only appears once
scrolled, where it separates the floating bar from content passing under it.

### Scope: the chrome only

Every rule in `style.css` is scoped to `.site-header`, `.site-footer`,
`.drawer`, `.mobile-actions` or `.brand__logo`. Nothing in it targets
Elementor sections, containers, columns or widgets, and nothing sets a global
page width, position or z-index. The page between the header and the footer
belongs to Elementor.

Page content width belongs in **Elementor → Site Settings → Layout**.

## Changing the design

The four files in `assets/css/` are the original project stylesheets, copied
in unchanged. Site-wide design changes belong there — or better, in Elementor
Site Settings so they apply to Elementor widgets too.

`style.css` is for WordPress-specific fixes only, all of them scoped to the
header and footer: header position and colours, full-bleed chrome, the admin
bar offset for the drawer, and logo sizing. Add your own overrides at the
bottom of that file.

If you edit anything in `assets/`, bump `NYC_ICON_VERSION` in `functions.php`
so browsers fetch the new file instead of a cached one.
