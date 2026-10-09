# South African community reporting platform

Planning baseline — 9 October 2026. No live platform or SAPS data import has been implemented.

## Agreed scope

- South Africa first, with later country expansion.
- Start with stolen vehicles; support other stolen belongings later.
- Facebook-style community feed, local communities, comments, sharing, search, area alerts, and recovery updates.
- Structured reports containing vehicle photos and identifying details, approximate theft area and date, and reported/recovered/closed status.
- No SAPS case-number fields or document requests for case numbers. No implied SAPS affiliation or police verification.
- Private sightings; protect exact sighting locations and reporter contact information.
- Preferred contact method: platform messaging, WhatsApp, telephone, or email. Platform messaging is the default. Include contact hours and a sightings-only preference. Public disclosure of external contact details requires a separate informed choice; preferences can be changed and visibility withdrawn.
- Hotspot zones with dates, counts, category filters, and zone-following alerts. Community hotspots use broad areas, suppress sparse groups, and exclude duplicate, disputed, and removed reports. Thresholds require privacy assessment before launch.

## Privacy and legal implementation requirements

Design for POPIA and applicable South African law; do not claim certified or guaranteed compliance. Document the lawful basis, purpose, visibility, and retention of each data field. Avoid unnecessary identity documents and home addresses. Assess registration numbers and photos for personal information. Moderate accusations, doxxing, and third-party information. Provide access, correction, objection, and deletion-request processes, with lawful retention exceptions. Implement security, access controls, processor agreements, overseas-transfer assessment, and breach response. Establish Information Officer responsibilities and registration, a PAIA manual and records-access procedures. Assess criminal-allegation data, children's information, electronic communications and any paid features before launch. Obtain an implementation-specific South African legal review.

## SAPS statistics layer

Use only aggregate published statistics initially. Target theft of motor vehicles and motorcycles and carjacking as separate categories. Do not turn aggregate totals into individual stolen-vehicle listings.

Keep SAPS and community layers and counts separate: overlap cannot be reliably deduplicated without individual records. Label SAPS figures with reporting period, source, geography, and publication/import dates. Historical counts are not live alerts, precise incident coordinates, or a measure of individual risk.

Map station-area totals only to verified, appropriately licensed station-area boundaries. If boundaries are unavailable, show a station summary marker with an explicit explanation that the marker is not an incident location. Do not interpolate station counts into street-level hotspots.

### Proposed import fields

source_dataset_id, source_url, source_file_hash, publication_date, retrieved_at, period_start, period_end, financial_year, quarter, province, station_source_identifier (if supplied), station_name, source_crime_category, normalized_crime_category, incident_count, geography_type, boundary_source, boundary_version, reuse_status, validation_status.

### Import and publication checks

1. Retrieve a published official file and retain its source metadata privately.
2. Confirm the precise reporting period and category definitions.
3. Validate nonnegative integer counts, missing values, duplicates, station matching, and totals only where comparable official totals exist. Missing is not zero; do not sum overlapping periods or national and station rows together.
4. Confirm the legal basis and any conditions for extracting and republishing statistics and for using map boundaries. Attribution alone does not establish permission.
5. Review the resulting dataset before public release. Preserve revisions and corrections.

### Research status

The official statistics index advertises station searches, presentations, and detailed statistics. Direct retrieval of the index and archive failed during this session; no current detailed file was downloaded or validated. An older official PDF was found as a structural reference. The reviewed website disclaimer did not establish an explicit open-data reuse licence. Reuse is unresolved, not proven prohibited. MySAPS app terms include separate restrictions and must not be assumed to grant access to app data. Until reuse is resolved, provide source links and keep the public imported layer disabled.

## Official sources

- Statistics: https://www.saps.gov.za/services/crimestats.php
- Archive: https://www.saps.gov.za/services/crimestats_archive.php
- Older structural reference: https://www.saps.gov.za/services/downloads/2024/2023-2024_-_4th_Quarter_WEB.pdf
- Website disclaimer: https://www.saps.gov.za/disclaimer.php
- MySAPS terms: https://www.saps.gov.za/services/mobile_app.php
- SAPS PAIA resources: https://www.saps.gov.za/resource_centre/paia/paialatest.php
- POPIA: https://www.gov.za/documents/protection-personal-information-act
- Regulator PAIA guidance: https://inforegulator.org.za/paia/

## Earlier chat context

The accessible user messages in “Stolen Vehicle Platform Idea” confirm a blog-like stolen-car posting/search community with differentiated communities. Earlier assistant replies were returned as content references and could not be read; additional decisions from those replies remain unconfirmed.
