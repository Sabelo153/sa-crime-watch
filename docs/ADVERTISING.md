# Free access and direct sponsorship

The application remains free for residents. Direct sponsor placements can fund
hosting and operation once you secure paying advertisers; the code itself does
not produce revenue or guarantee that advertising will cover costs.

## Available workflow

Visitors use /advertise to submit a business enquiry. Enquiries are encrypted,
visible only in the moderator sponsor dashboard and retained for 90 days.
The site does not send an email automatically; staff review enquiries at
/moderation/sponsors. The daily scheduler purges expired enquiries.

Register your staff account and grant moderator access with:

    php artisan watch:moderator your-email@example.com

Then open /moderation/sponsors to enter a booking:

- Business name, headline, short description and HTTPS destination.
- Homepage banner or report-results sidebar.
- Start/end dates, optional creative photo and an enable switch.
- Review the advertiser, content, destination and booking before enabling.

Ads become visible only when enabled and within their inclusive date range.
If several bookings overlap in one slot, the newest booking wins. Arrange
non-overlapping dates for a predictable schedule; there is no impression quota
or rotation algorithm. The pause switch removes a booking immediately.
Photos use the existing metadata-stripping upload service.

Unbooked spaces show an honest advertise-here invitation. Advertisements are
labelled, and outbound links use sponsored/noopener/noreferrer attributes.
There are no third-party scripts, cookies, fingerprinting, visitor-level
tracking or claimed audience figures. Advertisers cannot access tips or
enquiries, and payment does not grant report-moderation privileges.

Ads are limited to public homepage browsing. Report-detail/tip pages,
registration/login, personal reports and moderation screens have no ad slots.
Ads never cover controls, interrupt submissions or look like incident reports.

## Turning the spaces into revenue

Agree directly with businesses on a flat sponsorship term, price, creative,
placement and invoice/payment method. Confirm payment using your chosen
business process before enabling a booking. Keep the financial record outside
this MVP; there is no payment processing or automatic invoicing.

Approach suitable local businesses with the /advertise page and an honest
description of the available audience. Set rates after you know actual site
traffic and hosting costs. The site does not claim an audience size or display
fabricated sponsors to simulate bookings.

If you later choose an ad network, its account, site approval, privacy/consent
requirements and provider script configuration need a separate integration.
No ad-network account has been created, no external scripts added, and nothing
has been published or deployed.

## Operation

Run migrations after upgrading. Run php artisan schedule:work during local
development; use a scheduled Laravel command runner in the eventual host.
Manual enquiry cleanup:

    php artisan watch:purge-sponsor-inquiries

Sponsor records have no delete UI; pause a record to remove public content
while preserving the booking history. A future edit/invoice/reporting workflow
can be added as the sponsorship operation grows.
