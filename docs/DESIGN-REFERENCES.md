# Crime/public-safety design reference set

Research date: 9 October 2026. This is a representative set of 30 established
commercial products and official public-safety/data portals, not a measured
global popularity ranking. Live pages, indexed primary-source descriptions
and documentation were consulted. Some interactive apps expose only a shell
to the research tool; access limits are identified below. No claim is made
that every site's full interface was visually inspected.

## Reference register

| # | Primary source | Research access and relevant pattern |
| --- | --- | --- |
| 1 | [CrimeMapping](https://www.crimemapping.com/) | Page reviewed: location/agency search, data explanation and help |
| 2 | [SpotCrime](https://spotcrime.com/map) | Page text: geographic browsing and prominent area search |
| 3 | [CityProtect](https://cityprotect.com/map) | Indexed primary overview; interactive shell: agency/incident filters and list affordance |
| 4 | [LexisNexis Community Crime Map](https://communitycrimemap.com/) | Interactive shell; product descriptions consulted as secondary access to the same provider |
| 5 | [NeighborhoodScout](https://www.neighborhoodscout.com/) | Page reviewed: prominent location search and data context; broader property product |
| 6 | [CrimeGrade](https://crimegrade.org/) | Public page available; location-oriented crime information reference |
| 7 | [CrimeoMeter](https://www.crimeometer.com/) | Public provider page available; crime data/coverage context |
| 8 | [Police.uk](https://www.police.uk/) | Limited direct page extraction; reference candidate for public local-policing navigation |
| 9 | [data.police.uk](https://data.police.uk/docs/method/crimes-at-location/) | Primary API documentation/indexed pages: location/date boundaries and coverage caveats |
| 10 | [Metropolitan Police stats/data](https://www.met.police.uk/sd/stats-and-data/) | Public statistics landing page available; organized access to data |
| 11 | [NYPD CompStat](https://compstat.nypdonline.org/) | Indexed primary page; direct fetch timed out: overview counts, periods and data caveats |
| 12 | [Chicago CLEARMAP](https://gis.chicagopolice.org/) | Interactive shell only; mapping reference candidate |
| 13 | [LAPD Crime Mapping/COMPSTAT](https://www.lapdonline.org/crime-mapping-and-compstat/) | Primary indexed overview; direct fetch timed out: named data/reporting sections |
| 14 | [SFPD Crime Dashboard](https://www.sanfranciscopolice.org/stay-safe/crime-data/crime-dashboard) | Page reviewed: filters, incident definitions and changing-data explanation |
| 15 | [Seattle Crime Dashboard](https://seattle.gov/police/information-and-data/data/crime-dashboard) | Primary indexed page; direct access forbidden: terminology and methodology explanation |
| 16 | [DC Crime Cards](https://crimecards.dc.gov/) | Interactive shell only; compact crime-summary reference candidate |
| 17 | [Baltimore Crime Stats](https://www.baltimorepolice.org/crime-stats) | Page reviewed: map/list/data entry points and preliminary-data caveats |
| 18 | [Boston Police Crime Stats](https://police.boston.gov/category/crime-stats/) | Primary indexed listing reviewed: clearly dated report summaries |
| 19 | [Philadelphia Police Interactive Map](https://www.phillypolice.com/district/district-gis/) | Primary indexed interface: date/category controls and geographic layers |
| 20 | [Toronto Public Safety Data Portal](https://data.tps.ca/) | Interactive shell; [police overview](https://www.tps.ca/data-maps/open-data/) reviewed: grouped maps/data access |
| 21 | [Vancouver GeoDASH overview](https://vpd.ca/crime-statistics/) | Page and FAQ reviewed: location privacy, local areas and interpretation caveats |
| 22 | [Ottawa Community Safety Data Portal](https://data.ottawapolice.ca/) | Primary [update overview](https://pia.ottawapolice.ca/updated-community-safety-data-portal-le-portail-de-donnees-sur-la-securite-communautaire-mise-a-jour-2) indexed: easier searching and descriptive purpose statement |
| 23 | [Calgary Community Safety tools](https://www.calgary.ca/cps/public-services/calgary-police-mobile-app.html) | Primary app overview and dashboard guide reviewed: reporting, tips, community/category controls |
| 24 | [Edmonton Neighbourhood Crime Mapping](https://www.edmontonpolice.ca/CrimeFiles/NeighbourhoodCrimeMapping) | Primary indexed overview: community engagement and transparent information sharing |
| 25 | [NSW BOCSAR Crime and Policing](https://bocsar.nsw.gov.au/statistics-dashboards/crime-and-policing.html) | Page reviewed: grouped local-area tools and guides to interpreting data |
| 26 | [Victoria Crime Statistics Agency](https://www.crimestatistics.vic.gov.au/) | Direct site unavailable; primary indexed reports describe geographic hierarchy and location quality |
| 27 | [New Zealand Police data](https://www.police.govt.nz/about-us/publications-statistics/data-and-statistics/policedatanz) | Primary indexed page reviewed: device-specific snapshots and privacy boundaries |
| 28 | [SAPS crime statistics](https://www.saps.gov.za/services/crimestats.php) | Direct page unavailable; primary published reports/releases consulted for South African context |
| 29 | [ISS Crime Hub](https://crimehub.org/wizard) | Page reviewed: province, municipality, period/category controls and contextual report access |
| 30 | [South Australia Police crime statistics](https://www.police.sa.gov.au/about-us/crime-statistics-map) | Public page available: crime statistics/map entry point |

## Design decisions applied to SA Crime Watch

1. Search is the primary task: registration/community controls stay visible,
   with additional province, vehicle and date filters in a native disclosure.
   Inspired by CrimeMapping, SpotCrime, Philadelphia and Crime Hub.
2. Local geography stays prominent in navigation, report summaries and
   community shortcuts. The MVP uses named communities, without collecting
   exact incident addresses or coordinates.
3. Report cards distinguish state, registration, photograph, vehicle,
   geography and theft date. Missing photos have an honest placeholder.
4. The dashboard shows actual approved-missing and recovered report counts
   and available communities. It labels these as platform data. Pending
   reports and private tips do not contribute to public summary counts.
5. Registration explains the purpose and privacy boundaries alongside the
   form, without claiming a police affiliation or guaranteed anonymity.
6. Clear navigation and responsible reporting guidance follow the public
   service emphasis of the police portals. A skip link and visible focus
   states support keyboard use.
7. The visual system is original: navy/graphite surfaces, restrained amber
   accents, custom line icons, a decorative community illustration and
   responsive layouts. No third-party branding or assets were copied.

This is a redesign of the existing stolen-vehicle MVP. Map interfaces informed
the local search hierarchy; no real-time map, crime feed, notification system
or police integration has been added. There are no simulated incidents or
fabricated activity counts.
