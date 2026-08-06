# Project Status

## Last Updated
6 August 2026

## Overall Status
GGM System remains in active development on the dev branch. Core modules are implemented, and recent work has focused on stabilizing quote and order workflows, improving input/form UX, and resolving client-reported issues. The current dev branch is ahead of main, and the working tree is currently clean.

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
- Schedule module support for New Memorial, Added Inscription, Renovation, and Washdown
- PDF generation for quotations, orders, inscriptions, payment receipts, and statements

### Masterfile CRUD
- Cemeteries, Burial Societies, Grave Spaces, Letter Types, Materials, Accessories, Based Ledgers, Order Types, Colours

### Configuration & Authentication
- User management, Locations, Account Levels, Modules
- Laravel Breeze authentication flow (login, registration, password reset, email verification)

### Recent Enhancements
- Custom cemetery and burial society entry workflows with duplicate validation
- Consistent "Others" workflows for relevant quote fields
- "No Consecration" workflow with conditional date options
- Improved input component UX and date formatting handling
- Quote cost persistence fix for new quote creation

## In Progress
- Regression testing on quote and order workflows
- Preparing the latest changes for merge from dev into main
- Expanding automated test coverage for critical business flows

## Pending
- [ ] Add further automated tests for quote and order workflows
- [ ] Verify the full CRUD flow for Renovation and Washdown schedules
- [ ] Merge dev into main once the latest changes are validated

## Blocked
- None currently identified

## Known Issues
- Automated test coverage is still limited
- Some legacy unused markup remains in the quote form
- The schedule module still needs full end-to-end validation

## Recent Changes

| Date | Description |
|------|-------------|
| 2026-08-06 | Updated project status to reflect recent quote/order fixes and form UX improvements |
| 2026-08-06 | Applied date format handling update |
| 2026-08-06 | Refactored input component for improved consistency and usability |
| 2026-08-06 | Updated OrderService logic to address the quote cost persistence issue |
| 2026-08-06 | Fixed quote cost saving on initial creation |
| 2026-07-31 | Improved quote form UI/UX and burial society reset behavior |
| 2026-07-29 | Added "No Consecration" workflow and consistent "Others" option handling |
| 2026-07-29 | Implemented custom cemetery and burial society entry workflows |
| 2026-07-09 | Initialized Schedule Renovation & Washdown support |
| 2026-07-07 | Applied Jessica's feedback and added supporting template files |
| 2026-07-06 | Updated Added Inscription module |

## Next Steps
1. Validate the latest quote and order fixes in a full test pass.
2. Continue expanding automated test coverage.
3. Merge dev into main after confirming the workflow changes are stable.
