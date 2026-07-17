# Omeka S Digital Archive API

> [!WARNING]
> **OBSOLETE — no longer used**
> This service is retired. The active digital archive API now lives in `pset-laravel`, backed by Paperless-ngx and MariaDB. Keep this repository only for historical reference.

PHP middleware that wraps [Omeka S](https://omeka.p-set.org/api/) REST API. Provides simplified JSON endpoints for the **Prawase Digital Archive** (คลังข้อมูลดิจิทัล ศาสตราจารย์นายแพทย์ประเวศ วะสี).

- Proxies requests to Omeka S backend with API key authentication
- Converts Buddhist Era dates (พ.ศ.) → Common Era (ค.ศ.) automatically
- Replaces Omeka S JSON key prefixes (`o:`, `dcterms:`, `@value`) → underscore format (`o_`, `dcterms_`, `a_value`)
- Returns clean, flat JSON arrays
- Built on CodeIgniter 3 framework + standalone PHP endpoint files

## Base URL

```
Production : https://omeka.p-set.org/omekaapi/api/
Local      : http://localhost/omekaapi/api/
Docker     : http://localhost:9876/
```

---

## API Endpoints

### 1. Monthly Curated Content — `monthlylist`

เนื้อหาคัดสรร — Curated items filtered by year/month with pagination.

**File:** `monthlylist.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `crty` | string | ✅ | — | Year (ค.ศ.), e.g. `2023` |
| `crtm` | string | ❌ | all months | Month, e.g. `04` (April) |
| `limitpermonth` | int | ❌ | `99` | Max items per month |

**Example:**
```
GET /api/monthlylist?crty=2023&crtm=04&limitpermonth=10
```

**Response:** `application/json`
```json
[
  {
    "month": "04",
    "year": "2023",
    "data": [
      {
        "id": 2114,
        "thumbnail": "https://omeka.p-set.org/files/large/...",
        "cates": [10, 12],
        "catenames": ["สิ่งแวดล้อม", "สุขภาพ"],
        "title": "ชื่อรายการ",
        "created": "2023-04-15",
        "creator": "ประเวศ วะสี"
      }
    ]
  }
]
```

---

### 2. Popular Items — `popularlist`

เนื้อหายอดนิยม — Top items ranked by total page hits.

**File:** `popularlist.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `toplimit` | int | ✅ | `5` | Number of top items to return |

**Example:**
```
GET /api/popularlist?toplimit=10
```

**Response:**
```json
[
  {
    "id": 2114,
    "total_hits": 350,
    "thumbnail": "https://omeka.p-set.org/files/large/...",
    "cates": [10, 12],
    "catenames": ["สิ่งแวดล้อม", "สุขภาพ"],
    "title": "ชื่อรายการ",
    "created": "2023-04-15",
    "creator": "ประเวศ วะสี"
  }
]
```

**Note:** Uses Omeka S stats module (`/api/stats`). Filtered to `resource_template_id=2` (Prawase template only).

---

### 3. Flashback List — `flashbacklist`

ย้อนสำรวจความทรงจำสำคัญ — Items from N years ago in a specific month.

**File:** `flashbacklist.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `skipy` | int | ✅ | — | Years to look back, e.g. `20` = 20 years ago |
| `skipm` | string | ❌ | `00` (all months) | Target month, e.g. `04` (April). `00` = all months |
| `limitperpage` | int | ❌ | `100` | Max items returned |

**Example:**
```
GET /api/flashbacklist?skipy=20&skipm=04&limitperpage=10
```

**Response:**
```json
[
  {
    "id": 1966,
    "thumbnail": "https://...",
    "cates": [10],
    "catenames": ["สิ่งแวดล้อม"],
    "title": "ชื่อรายการ",
    "created": "2003-04-01",
    "creator": "ประเวศ วะสี"
  }
]
```

**Note:** Calculates target year as `currentYear - skipy`. Searches `dcterms:date` property containing that year-month combination.

---

### 4. Explore by Subject — `letsgolist`

ชวนออกสำรวจ — Random items from random subjects (item sets).

**File:** `letsgolist.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `limit` | int | ✅ | `10` | Number of random items to return |

**Example:**
```
GET /api/letsgolist?limit=10
```

**Response:**
```json
[
  {
    "id": 2114,
    "thumbnail": "https://...",
    "cates": [10],
    "catenames": ["สิ่งแวดล้อม"],
    "title": "ชื่อรายการ",
    "created": "2023-04-15",
    "creator": "ประเวศ วะสี"
  }
]
```

**Note:** Fetches all item sets, picks random ones, then fetches random items from each selected set.

---

### 5. Item Detail — `item`

เนื้อหารายละเอียดรายการ — Full detail of a single item including all media files.

**File:** `item.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `id` | int | ✅ | — | Omeka item ID |

**Example:**
```
GET /api/item?id=2114
```

**Response:**
```json
[
  {
    "id": 2114,
    "title": "ชื่อรายการ",
    "cates": ["สิ่งแวดล้อม", "สุขภาพ"],
    "subject": ["หัวเรื่อง1", "หัวเรื่อง2"],
    "creator": ["ประเวศ วะสี"],
    "detail": "รายละเอียด...",
    "abstract": "บทคัดย่อ...",
    "date": "2023-04-15",
    "type": ["หนังสือ"],
    "media": [
      {
        "source": "https://...",
        "id_media": 5001,
        "media_type": "application/pdf",
        "filename": "document.pdf",
        "original_url": "https://omeka.p-set.org/files/original/...",
        "thumbnail": "https://omeka.p-set.org/files/large/..."
      }
    ],
    "thumbnail": "https://omeka.p-set.org/files/large/...",
    "created": "2023-04-15",
    "rights": ["CC BY-NC-SA 4.0"],
    "coverage": ["กรุงเทพมหานคร"],
    "staffpick": false
  }
]
```

**Notes:**
- If media source is a valid YouTube URL → `media_type` set to `"video/link"`
- `staffpick` = `true` when `dcterms:issued` field exists (indicates staff-curated)
- `date` auto-converts B.E. > 2500 → C.E.
- Returns `["n.d."]` if `id` not provided

---

### 5b. Item Detail v2 — `item2`

Same as `item` but older variant. Returns one object per media file (flat) instead of nested `media` array. Contains debug `print_r` output — **not recommended for production**.

**File:** `item2.php`

---

### 6. Related Items — `relations`

คลังเอกสารอิเล็กทรอนิกส์ที่เกี่ยวข้อง — Items related to a given item (same item sets), randomly selected.

**File:** `relations.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `id` | int | ✅ | `0` | Source item ID to find relations for |
| `limit` | int | ✅ | `8` | Number of related items to return |

**Example:**
```
GET /api/relations?id=1966&limit=8
```

**Response:**
```json
[
  {
    "id": 2050,
    "thumbnail": "https://...",
    "cates": [10, 12],
    "catenames": ["สิ่งแวดล้อม", "สุขภาพ"],
    "date": ["2023-04-15"],
    "title": "ชื่อรายการ",
    "creator": "ประเวศ วะสี",
    "created": "2023-04-15"
  }
]
```

**Note:** Finds item's `item_set` IDs → queries items with same item sets → randomly picks `limit` items from results. Uses `resource_class_id=23`.

---

### 7. Archive List — `archivelist`

คลังเอกสารอิเล็กทรอนิกส์ — Paginated list of all public archive items.

**File:** `archivelist.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | ❌ | `1` | Page number |
| `limitperpage` | int | ❌ | `10` | Items per page |

**Example:**
```
GET /api/archivelist?page=1&limitperpage=10
```

**Response:**
```json
[
  {
    "id": 2114,
    "thumbnail": "https://...",
    "cates": [10],
    "catenames": ["สิ่งแวดล้อม"],
    "title": "ชื่อรายการ",
    "created": "2023-04-15",
    "creator": "ประเวศ วะสี"
  }
]
```

---

### 8. Archive Groups — `archivegroup`

หมวดหมู่คลังเอกสารอิเล็กทรอนิกส์ — List all item set categories (collections).

**File:** `archivegroup.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| _(none)_ | — | — | — | No parameters |

**Example:**
```
GET /api/archivegroup
```

**Response:**
```json
[
  {
    "id": 10,
    "title": "สิ่งแวดล้อม"
  },
  {
    "id": 12,
    "title": "สุขภาพ"
  }
]
```

---

### 9. Archive List with Filter — `archivelistfilter`

คลังเอกสารอิเล็กทรอนิกส์ (กรองข้อมูล) — Filtered, sorted, paginated archive search.

**File:** `archivelistfilter.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | ❌ | `1` | Page number |
| `limitperpage` | int | ❌ | `10` | Items per page |
| `cates` | string | ❌ | `0` (all) | Comma-separated item set IDs, e.g. `10,12,17`. `0` = all |
| `search` | string | ❌ | _(empty)_ | Search keyword |
| `sort_by` | string | ❌ | `title` | Sort field: `title` or `date` |
| `sort_order` | string | ❌ | `asc` | Sort direction: `asc` or `desc` |
| `media_types` | string | ❌ | `0` (all) | Comma-separated media types: `book`, `image`, `vdo`, `sound`, `doc`. `0` = all |

**Example:**
```
GET /api/archivelistfilter?page=1&limitperpage=10&cates=10,12,17,18&search=ประเวศ&sort_by=title&sort_order=asc&media_types=0
```

**Response:**
```json
{
  "total": 150,
  "data": [
    {
      "id": 2114,
      "thumbnail": "https://...",
      "cates": [10],
      "catenames": ["สิ่งแวดล้อม"],
      "title": "ชื่อรายการ",
      "created": "2023-04-15",
      "creator": "ประเวศ วะสี"
    }
  ]
}
```

**Note:** `media_types` mapping:
| Value | MIME Types |
|---|---|
| `book` | `application/pdf` |
| `image` | `image/jpeg`, `image/png` |
| `vdo` | `video/mp4` |
| `sound` | `audio/mpeg` |
| `doc` | `application/msword`, `application/vnd.openxmlformats-officedocument.wordprocessingml.document`, `application/vnd.ms-powerpoint`, `application/vnd.openxmlformats-officedocument.presentationml.presentation`, `application/vnd.openxmlformats-officedocument.spreadsheetml.sheet` |

---

### 10. Media Statistics — `statlist`

สำรวจจากชนิดของสื่อที่หลากหลาย — Count of media files grouped by type.

**File:** `statlist.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| _(none)_ | — | — | — | No parameters |

**Example:**
```
GET /api/statlist
```

**Response:**
```json
[
  {
    "documents": 1250,
    "photos": 3400,
    "sounds": 120,
    "videos": 85,
    "books": 500,
    "total_items": 5355
  }
]
```

**Note:** Aggregates counts by querying Omeka S `infos/media` endpoint with specific MIME type filters:
- `documents` = PDF + DOC + PPT
- `photos` = JPEG + PNG
- `sounds` = MPEG audio
- `videos` = MP4
- `books` = counted separately
- `total_items` = from `infos` endpoint with `resource_template_id=2`

---

### 11. Staff Picks — `staffpicks`

เนื้อหาโดดเด่นที่คัดสรรจากทีมงาน — Staff-curated items grouped by month. Uses `dcterms:issued` field (property ID 23) to identify staff picks.

**File:** `staffpicks.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `revm` | int | ✅ | `3` | Number of months to look back from current month |
| `limitpermonth` | int | ❌ | `99` | Max items per month. `0` = all |

**Example:**
```
GET /api/staffpicks?revm=4&limitpermonth=8
```

**Response:**
```json
[
  {
    "month": "06",
    "year": "2026",
    "data": [
      {
        "id": 2114,
        "thumbnail": "https://...",
        "cates": [10, 12],
        "catenames": ["สิ่งแวดล้อม", "สุขภาพ"],
        "title": "ชื่อรายการ",
        "created": "2023-04-15",
        "creator": "ประเวศ วะสี"
      }
    ]
  }
]
```

**Note:** Month array goes backwards from current month. If month < 1, wraps to previous year (e.g. current=Feb, revm=4 → Feb, Jan, Dec(prev year), Nov(prev year)).

---

### 12. Popular All (by Year) — `popularall`

รวมเนื้อหายอดนิยมรายปี — Popular items grouped by year, going back N years.

**File:** `popularall.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `revm` | int | ✅ | `3` | Number of years to look back |
| `toplimit` | int | ❌ | `999` | Max items per year. `0` = use 999 |

**Example:**
```
GET /api/popularall?revm=3&toplimit=0
```

**Response:**
```json
[
  {
    "year": 2026,
    "data": [
      {
        "id": 2114,
        "title": "ชื่อรายการ",
        "total_hits": 350,
        "thumbnail": "https://...",
        "date": "2023-04-15",
        "cates": ["สิ่งแวดล้อม"]
      }
    ]
  },
  {
    "year": 2025,
    "data": ["n.d."]
  }
]
```

**Note:** Groups items by `created`/`modified` year from Omeka S stats. `data` = `["n.d."]` when no items found for that year.

---

### 13. Archive Group Filter — `archivegroupfilter`

แสดงรายการคอลเลกชั่นตาม Class — List item sets filtered by resource class names.

**File:** `archivegroupfilter.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `class` | string | ✅ | — | Comma-separated resource class names, e.g. `Collection,Dataset,Event,Image` |

**Example:**
```
GET /api/archivegroupfilter?class=Collection,Dataset,Event,Image
```

**Response:**
```json
[
  {
    "id": 10,
    "title": "สิ่งแวดล้อม"
  }
]
```

---

### 14. Archive Properties — `archiveproperties`

แสดงรายการ property — List available Dublin Core (dcterms) properties.

**File:** `archiveproperties.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| _(none)_ | — | — | — | No parameters |

**Example:**
```
GET /api/archiveproperties
```

**Response:**
```json
[
  {
    "id": 1,
    "label": "Title",
    "term": "dcterms_title"
  }
]
```

**Note:** Fetches from Omeka S `/api/properties` (vocabulary ID 1 = Dublin Core). Useful for building dynamic filter UIs.

---

### 15. Archive List All (Advanced Filter) — `archivelistall`

แสดงรายการขั้นสูง — Full-featured archive search with class, collection, property, and keyword filters.

**File:** `archivelistall.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `class` | string | ❌ | — | Comma-separated resource class names: `Collection,Dataset,Event,Image` |
| `search` | string | ❌ | _(empty)_ | Search keyword |
| `collection` | string | ❌ | — | Comma-separated item set IDs, e.g. `10,12,13,14` |
| `property` | string | ❌ | — | Comma-separated dcterms properties to search in, e.g. `dcterms_subject,dcterms_description` |
| `page` | int | ❌ | `1` | Page number |
| `limitperpage` | int | ❌ | `100` | Items per page |

**Example:**
```
GET /api/archivelistall?class=Collection,Dataset,Event,Image&search=สสส&collection=10,12,13,14&property=dcterms_subject,dcterms_description,dcterms_abstract,dcterms_coverage,dcterms_tableOfContents&page=1&limitperpage=100
```

**Response:**
```json
{
  "total": 50,
  "data": [
    {
      "id": 2114,
      "thumbnail": "https://...",
      "cates": [10],
      "catenames": ["สิ่งแวดล้อม"],
      "title": "ชื่อรายการ",
      "created": "2023-04-15",
      "creator": "ประเวศ วะสี"
    }
  ]
}
```

**Note:** Use endpoint 14 (`archiveproperties`) to discover valid property names. Use endpoint 13 (`archivegroupfilter`) to discover valid collection IDs.

---

### 16. Archive List All Pages (Public) — `archivelistallpage`

แสดงรายการข้อมูล Visibility=Public — Paginated list of all public items.

**File:** `archivelistallpage.php`

| Parameter | Type | Required | Default | Description |
|---|---|---|---|---|
| `page` | int | ❌ | `1` | Page number |
| `limitperpage` | int | ❌ | `50` | Items per page |

**Example:**
```
GET /api/archivelistallpage?page=1&limitperpage=50
```

**Response:**
```json
{
  "total": 5000,
  "data": [
    {
      "id": 2114,
      "thumbnail": "https://...",
      "title": "ชื่อรายการ",
      "created": "2023-04-15",
      "creator": "ประเวศ วะสี"
    }
  ]
}
```

---

## Common Response Notes

| Field | Description |
|---|---|
| `id` | Omeka S item ID (integer) |
| `title` | Item title (`o:title`) |
| `thumbnail` | URL to large thumbnail image. Empty string if none |
| `cates` | Array of item set IDs (integers) or item set names (strings) — varies by endpoint |
| `catenames` | Array of category names from `dcterms:coverage` |
| `date` | Document date from `dcterms:date`, auto-converted from B.E. to C.E. |
| `created` | Creation date from `dcterms:created`, auto-converted from B.E. to C.E. |
| `creator` | Author/creator from `dcterms:creator` |
| `"n.d."` | "No data" — returned when field has no value or query returns empty |

### Date Conversion (B.E. → C.E.)

All date fields auto-convert Thai Buddhist Era to Common Era:
- Input `2566-04-15` → Output `2023-04-15` (subtracts 543 when year > 2500)
- Supports formats: `YYYY-MM-DD`, `YYYY-MM`, `YYYY`, `YYYY/MM/DD`

---

## Project Structure

```
omekaapi/
├── config_header.php         # CORS headers (Access-Control-Allow-Origin: *)
├── config_url.php            # Omeka S base URLs + API configuration
├── custom_function.php       # Shared functions: API caller, date converter, YouTube validator
├── index.php                 # CodeIgniter 3 front controller
├── composer.json             # CodeIgniter dependency config
├── Dockerfile                # Apache/PHP Docker image
├── docker-compose.yml        # Docker Compose config (port 9876)
│
├── monthlylist.php           # [1] Monthly curated content
├── popularlist.php           # [2] Popular items by hits
├── flashbacklist.php         # [3] Flashback items (N years ago)
├── letsgolist.php            # [4] Random explore by subject
├── item.php                  # [5] Item full detail
├── item2.php                 # [5b] Item detail (legacy/debug variant)
├── relations.php             # [6] Related items
├── archivelist.php           # [7] Archive list (paginated)
├── archivegroup.php          # [8] Archive category groups
├── archivelistfilter.php     # [9] Archive list with filters
├── archivelistfilter2.php    # [9b] Filter variant 2
├── archivelistfilter3.php    # [9c] Filter variant 3
├── statlist.php              # [10] Media type statistics
├── staffpicks.php            # [11] Staff-curated picks by month
├── popularall.php            # [12] Popular items grouped by year
├── archivegroupfilter.php    # [13] Archive groups by resource class
├── archiveproperties.php     # [14] Available Dublin Core properties
├── archivelistall.php        # [15] Advanced archive search
├── archivelistallpage.php    # [16] All public items (paginated)
│
├── monthlylist2.php          # Variant of monthlylist
├── monthlylist_config_header.php  # Config header variant for monthlylist
├── archivelistfilter - Copy.php   # Backup copy
├── api_sss1.php              # SSS integration endpoint 1
├── api_sss2.php              # SSS integration endpoint 2
├── readme.php                # API documentation page (HTML)
├── test2.php                 # Test/debug file
├── application/              # CodeIgniter application directory
├── system/                   # CodeIgniter system directory
└── uploads/                  # Upload directory
```

---

## Docker

Build and run:

```bash
docker compose up --build
```

API available at [http://localhost:9876](http://localhost:9876).

Stop:

```bash
docker compose down
```

---

## Configuration

### `config_url.php`

| Key | Value | Description |
|---|---|---|
| `omekas_url` | `https://omeka.p-set.org/api/` | Omeka S API base URL |
| `omekas_url_item` | `https://omeka.p-set.org/s/Prawase/item/` | Public item page URL |
| `omekas_url_img` | `https://omeka.p-set.org/` | Image base URL |
| `nd` | `n.d.` | Default "no data" string |

### Authentication

API key appended to all Omeka S requests:
- Query param: `key=UtDqbQ2yPkxkgmfW5ZAbHLDr2iJRBGA1`
- Header: `Authorization: Bearer UtDqbQ2yPkxkgmfW5ZAbHLDr2iJRBGA1`

### CORS

All endpoints return:
```
Access-Control-Allow-Origin: *
Content-Type: application/json; charset=UTF-8
Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE
```
