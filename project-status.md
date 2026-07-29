# Project Status

## Last Updated
29 July 2026, 4:00 PM AWST

## Overall Status
GGM System is in active development on the `dev` branch. Core order/quote management, customer handling, scheduling, inscription, and PDF generation are implemented. Recent work has focused on enhancing the quote form with custom-entry workflows and validation for Cemetery, Burial Society, and product-related masterfile fields.

The `dev` branch is 94 commits ahead of `main`. No merge to `main` has occurred recently.

## Completed

### Core Modules
- Customer management (CRUD, contacts, search modal)
- Quote creation and editing (full form with cost calculation, deposits, payments)
- Order lifecycle (create, edit, invoice, filter by type/date/user)
- Order cost section with additional costs, VAT, adjustments
- Payment recording with receipt and statement PDF generation
- Inscription management with approval workflow and PDF proofs
- Email sending with file attachments (OrderMail)
- File upload/delete per order
- Schedule module (New Memorial, Added Inscription, Renovation, Washdown) — index and form views exist
- PDF generation for quotations, orders, inscriptions, payment receipts, statements

### Masterfile CRUD
- Cemeteries, Burial Societies, Grave Spaces, Letter Types, Materials, Accessories, Based Ledgers, Order Types, Colours

### Configuration
- User management, Locations, Account Levels, Modules

### Authentication
- Laravel Breeze (login, registration, password reset, email verification)

### Quote Form Enhancements (29 July 2026)
- Custom Cemetery entry with "Others" option, inline input, entrance animation, and duplicate-name validation (client + server)
- Custom Burial Society Organization entry with "Others" workflow, animation, duplicate validation, and correct cemetery association
- "Others" inline input workflow for Material, Material Colour, Base Ledger, Letter Type, Accessories, Accessories Colour — replacing the old modal approach with inline inputs + client/server duplicate validation
- Generic `masterfile/check-duplicate` endpoint with whitelisted tables
- "No Consecration" checkbox workflow: conditionally shows TBA/Approx/ASAP radio group, reuses `fixed_required_by` as "Approximate Date" (shown only for Approx), backend conditionally processes consecration vs. radio fields

### Infrastructure
- Vite build system configured
- Laravel Pint for code style
- Pest test framework set up (scaffold only — no custom tests written)
- GitHub Actions workflows for tests, issues, PRs, changelog

## In Progress
- No tasks are actively in progress at this moment (latest commit is current with working tree clean)

## Pending
- [ ] Merge `dev` branch into `main`
- [ ] Write automated tests for quote form workflows (custom cemetery, burial society, masterfile "Others", no consecration)
- [ ] Write automated tests for order/payment flows
- [ ] Schedule module — verify full CRUD flow for Renovation and Washdown types (views and migrations exist, needs end-to-end verification)
- [ ] Production deployment preparation (environment configuration, asset build)

## Blocked
- None currently identified

## Known Issues
- **No automated test coverage** for business logic. Only Laravel Breeze scaffold tests exist (`ExampleTest`, `ProfileTest`, auth tests). Custom features have no test coverage.
- **ColourController** is an empty scaffold — all methods are stubs. Colours are managed through the generic masterfile duplicate-check endpoint and direct DB inserts in OrderService, but the dedicated controller has no functionality.
- **Colour model** has no `$fillable`, no `SoftDeletes`, and no `created_by` — differs from other masterfile models. New colour records created via the "Others" workflow will not have `created_by` tracking.
- The old `forOthersModal` modal markup still exists in `form.blade.php` (line ~1320) but its JS handlers have been replaced. The unused modal HTML could be cleaned up.
- `OrderService::order_upsert` does not use database transactions — simultaneous requests could theoretically create duplicate masterfile records despite duplicate checks.

## Recent Changes

| Date | Description |
|------|-------------|
| 2026-07-29 | Implement "No Consecration" checkbox with TBA/Approx/ASAP radio group and conditional `fixed_required_by` |
| 2026-07-29 | Implement consistent "Others" workflow for Material, Material Colour, Base Ledger, Letter Type, Accessories, Accessories Colour with inline inputs and server-side duplicate validation |
| 2026-07-29 | Implement "Others" workflow for Burial Society Organization with duplicate validation and cemetery association |
| 2026-07-29 | Implement Custom Cemetery entry with "Others" option, entrance animation, client + server duplicate validation |
| 2026-07-29 | Update package.json (Vite 6.4.3) |
| 2026-07-09 | Initialize Schedule Renovation & Washdown (migrations, views, seeders) |
| 2026-07-09 | Update source code, migrations, CSS, .gitignore |
| 2026-07-07 | Fix Jessica's feedback, add template files |
| 2026-07-06 | Update Added Inscription module |
| 2026-06-25 | Update Quote & Order filtering, web routes |
| 2026-06-08 | Update Order & Quote, Quote/Schedule improvements |
| 2026-05-28 | Bug fixes |
| 2026-05-21 | Schedule & Order PDF generation |
| 2026-05-15 | Update Order Form |

## Next Steps
1. Clean up unused `forOthersModal` HTML from the quote form template
2. Add database transactions to `OrderService::order_upsert` for masterfile record creation
3. Write Pest tests covering the custom cemetery, burial society, and "Others" masterfile workflows
4. Verify Schedule Renovation and Washdown end-to-end functionality
5. Align the Colour model with other masterfile models (add `$fillable`, `SoftDeletes`, `created_by`)
6. Merge `dev` into `main` once verified stable
