# Project Status

## Last Updated
31 July 2026, 4:30 PM AWST

## Overall Status
GGM System is in active development on the `dev` branch. Core modules are implemented. Recent work focused on quote form enhancements (custom entry workflows, "Others" options, No Consecration) and Select component UX improvements. Two client-reported bugs need resolution next week: burial society auto-"N/A" for custom cemeteries, and cost section data loss on quote creation.

The `dev` branch is ahead of `main`. There are uncommitted working-tree changes across 6 files (select component refactor, burial society reset fix, minor quote index tweak).

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
- Schedule module (New Memorial, Added Inscription, Renovation, Washdown) — views exist
- PDF generation for quotations, orders, inscriptions, payment receipts, statements

### Masterfile CRUD
- Cemeteries, Burial Societies, Grave Spaces, Letter Types, Materials, Accessories, Based Ledgers, Order Types, Colours

### Configuration
- User management, Locations, Account Levels, Modules

### Authentication
- Laravel Breeze (login, registration, password reset, email verification)

### Quote Form Enhancements (29 July 2026)
- Custom Cemetery entry with "Others" option, inline input, entrance animation, and duplicate-name validation (client + server)
- Custom Burial Society Organization entry with "Others" workflow, duplicate validation, and cemetery association
- "Others" inline input workflow for Material, Material Colour, Base Ledger, Letter Type, Accessories, Accessories Colour — inline inputs with client/server duplicate validation
- Generic `masterfile/check-duplicate` endpoint with whitelisted tables
- "No Consecration" checkbox workflow: conditionally shows TBA/Approx/ASAP radio group, reuses `fixed_required_by` as "Approximate Date"

### UI/UX Improvements (31 July 2026)
- Select component refactored: dropdown always opens below (no flip), max-height with scroll, ARIA attributes added
- Bootstrap Select `dropupAuto` globally disabled
- Burial Society dropdown resets to first option (placeholder) on cemetery change

## In Progress
- Uncommitted changes: Select component refactor, burial society reset, layout CSS/JS, quote form and index tweaks — ready for commit

## Pending
- [ ] Fix: Auto-set Burial Society to "N/A" when Cemetery is "Others" (client requirement — currently the burial society "Others" auto-select logic is commented out; needs redesign to set "N/A" instead)
- [ ] Fix: Quote Cost section not saving on initial creation (client-reported data loss)
- [ ] Commit and push current working-tree changes
- [ ] Write automated tests for quote form workflows
- [ ] Schedule module — verify full CRUD flow for Renovation and Washdown
- [ ] Merge `dev` into `main` once stable

## Blocked
- None currently identified

## Known Issues

### High Priority (client-reported, fix next week)
- **Burial Society should auto-set to "N/A" for custom cemetery** — When Cemetery is "Others" and a new name is entered, the Burial Society Organization should automatically be "N/A". Currently the code that auto-selects "Others" in the burial society dropdown is commented out in `quotes/index.js` (lines ~385–388, 402–405). Needs a new approach: either add an "N/A" option or auto-fill without requiring user input.
- **Quote Cost section data not persisting on create** — Cost data entered during new quote creation disappears after submission. Root cause likely in `OrderService::order_cost_upsert`: on create (`$id = false`), it creates a new `OrderCost` correctly, but the `$id` parameter is the *order* ID and `OrderCost::findOrFail($id)` on update uses it to look up the cost record by that same value — this works only because the cost and order share the same sequential ID by coincidence. The real issue may be that `$request->price_description` or `$request->price_amount` are null/empty arrays on create, causing the save to succeed with empty data. Needs investigation with actual form submission data.

### Medium Priority
- **No automated test coverage** for business logic. Only Laravel Breeze scaffold tests exist.
- **ColourController** is an empty scaffold — all methods are stubs.
- **Colour model** has no `$fillable`, no `SoftDeletes`, and no `created_by` — differs from other masterfile models.
- The old `forOthersModal` modal markup still exists in `form.blade.php` but its JS handlers have been replaced. Unused HTML.
- `OrderService::order_upsert` does not use database transactions — concurrent requests could create duplicate masterfile records.

### Low Priority
- `order_cost_upsert` uses order ID to look up `OrderCost` record via `findOrFail($id)` — fragile if IDs don't align.

## Recent Changes

| Date | Description |
|------|-------------|
| 2026-07-31 | Refactored Select component: disabled flip/dropup, added max-height scroll, ARIA attributes, keyboard focus indicator |
| 2026-07-31 | Fixed burial society dropdown to reset to first option (placeholder) on cemetery change |
| 2026-07-31 | Logged client-reported issues: Burial Society should auto-set to "N/A" for custom cemetery; Cost section data lost on quote creation |
| 2026-07-30 | Updated .env.example |
| 2026-07-29 | Created project-status.md |
| 2026-07-29 | Implement "No Consecration" checkbox with TBA/Approx/ASAP radio group and conditional `fixed_required_by` |
| 2026-07-29 | Implement consistent "Others" workflow for Material, Material Colour, Base Ledger, Letter Type, Accessories, Accessories Colour |
| 2026-07-29 | Implement "Others" workflow for Burial Society Organization |
| 2026-07-29 | Implement Custom Cemetery entry with "Others" option and duplicate validation |
| 2026-07-09 | Initialize Schedule Renovation & Washdown |
| 2026-07-07 | Fix Jessica's feedback, add template files |
| 2026-07-06 | Update Added Inscription module |

## Next Steps (prioritized for next week)
1. **Fix: Burial Society auto "N/A"** — When Cemetery is "Others", auto-set burial society to "N/A" (add an "N/A" option or submit a null/placeholder value). Update JS + backend to skip burial society validation for custom cemeteries.
2. **Fix: Cost section not saving on create** — Debug `order_cost_upsert` with form submission data, check if cost fields are included in the request on first save, verify `$request->order_id` is set correctly.
3. **Commit & push** current working-tree changes (select refactor, burial society reset).
4. Clean up unused `forOthersModal` modal HTML.
5. Add database transactions to `OrderService::order_upsert`.
6. Write Pest tests for the critical workflows.
