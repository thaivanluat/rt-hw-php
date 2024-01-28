# Laravel scrape data api with redis

A Laravel-Based Backend for Frontend Integration with a set of RESTful for scrape data

Comes with:
- web-app: `Laravel/PHP-8.2`
- web-server: `nginx`
- database: `redis`

## Setup
- `git clone https://github.com/thaivanluat/rt-hw-php.git`
- `cd rt-hw-php`
- `docker-compose up -d`
- `docker exec app composer install`
- `cp .env.example .env`
- `docker-compose exec app php artisan key:generate`

Now that all containers are up, we can open `http://127.0.0.1:8080/` on browser to check our laravel app is running

## Endpoints

| Method   | URL               | Description                              |
| -------- |-------------------| ---------------------------------------- |
| `GET`    | `/api/jobs`       | Retrieve all jobs.                      |
| `POST`   | `/api/jobs`      | Accept a JSON request body that includes array of URLs to scrape and HTML/CSS selectors.                       |
| `GET`    | `/api/jobs/{id}` | Return job details and scraped data from URL.                       |
| `DELETE`  | `/api/jobs/{id}` |  Remove job.                 |

### Create new job
Accept a JSON request body that includes array of URLs to scrape and HTML/CSS selectors.

**URL** : `/api/jobs/`

**Method** : `POST`

**Data example**
```json
{
    "request_body" : [
        {
            "url": "https://www.newsweek.com/deadly-foodborne-bacteria-hide-sanitizers-1864028",
            "selectors": {
                "title": "#header-container > header > h1",
                "article": "#v_article > div.article-body.v_text"
            }
        },
        {
            "url": "https://www.newsweek.com/mystery-mars-burp-belch-methane-search-life-1863907",
            "selectors": {
                "title": "#header-container > header > h1",
                "article": "#v_article > div.article-body.v_text"
            }
        }
    ]
}
```
**Response example**
```json
{
    "message": "Job created successfully",
    "job": {
        "id": "1",
        "status": "CREATED",
        "request_body": [
            {
                "url": "https://www.newsweek.com/deadly-foodborne-bacteria-hide-sanitizers-1864028",
                "selectors": {
                    "title": "#header-container > header > h1",
                    "article": "#v_article > div.article-body.v_text"
                }
            },
            {
                "url": "https://www.newsweek.com/mystery-mars-burp-belch-methane-search-life-1863907",
                "selectors": {
                    "title": "#header-container > header > h1",
                    "article": "#v_article > div.article-body.v_text"
                }
            }
        ],
        "scraped_data": null,
        "created_at": "2024-01-28 11:19:21",
        "updated_at": "2024-01-28 11:19:21"
    }
}
```

### Retrive job details

**URL** : `/api/jobs/{id}`

**Method** : `GET`

**Response example**
```json
{
    "id": "3",
    "status": "DONE",
    "request_body": [
        {
            "url": "https://www.newsweek.com/deadly-foodborne-bacteria-hide-sanitizers-1864028",
            "selectors": {
                "title": "#header-container > header > h1",
                "article": "#v_article > div.article-body.v_text"
            }
        },
        {
            "url": "https://www.newsweek.com/mystery-mars-burp-belch-methane-search-life-1863907",
            "selectors": {
                "title": "#header-container > header > h1",
                "article": "#v_article > div.article-body.v_text"
            }
        }
    ],
    "scraped_data": [
        {
            "url": "https://www.newsweek.com/deadly-foodborne-bacteria-hide-sanitizers-1864028",
            "scrapedData": {
                "title": "Deadly Foodborne Bacteria Found To Hide From Sanitizers",
                "article": "Our efforts to protect ourselves form potentially deadly bacteria may be in vain, scientists have warned.Listeria is the third leading cause of death from foodborne diseases in the U.S., the Centers for Disease Control and Prevention Reports. Roughly 1,600 people contract serious Listeria infections every year, resulting in 260 deaths. While the illness is usually mild, Listeria bacteria can spread beyond the intestines, potentially infecting other parts of the body.To avoid Listeria contamination, food suppliers often spray their produce with sanitizers to kill any bacteria. But, according to a new study from Penn State University, Listeria bacteria may be able to evade these sanitation protocols.The study, published in the journal Biofilm, demonstrates how other harmless species of bacteria in the fruit-packing environment can stick together and form a protective shield, called a biofilm, around the Listeria bacteria. Photo of microbes isolated from fruit packing environments growing in lab dishes. The new study suggests that common sanitizers may not be enough to protect us from foodborne infections. Photo of microbes isolated from fruit packing environments growing in lab dishes. The new study suggests that common sanitizers may not be enough to protect us from foodborne infections. Penn State function toggleCap(){const shortDesc=document.getElementById('short-cap-description');const fullDesc=document.getElementById('full-cap-description');const readMoreBtn=document.getElementById('read-more-cap');if(fullDesc.style.display==='none'){shortDesc.style.display='none';fullDesc.style.display='inline';readMoreBtn.textContent='Less'}else{shortDesc.style.display='inline';fullDesc.style.display='none';readMoreBtn.textContent='More'}} \"We found two groups of microorganisms in the tree fruit packing environments, Pseudomonadaceae and Xanthomonadaceae, that are very good at forming biofilms and protecting Listeria monocytogenes,\" co-author Jasna Kovac, a Professor of Food Safety at Penn State, said in a statement. \"Biofilms represent a physical barrier that reduces the effective diffusion and antimicrobial action of sanitizers and is hypothesized to increase Listeria monocytogenes' tolerance to sanitizers used in food processing facilities.\" Read more New infectious bacteria species found in hospital patient's blood Hummus recall as urgent warning issued over products Gastroenterologists reveal the exercises they do to reduce gut issues In other words, the protected bacteria are faced with a much lower dose of sanitizer, which they can more easily tolerate.The results highlight the need to assess how effective commonly used sanitizers are against the harmless biofilm-forming bacteria that can often be found in food processing environments.\"The findings of this research project will inform and enhance sanitation protocols and extension training efforts targeted at the tree-fruit industry to effectively control Listeria monocytogenes,\" co-author Luke LaBorde, a professor in food science and an expert in the tracking of Listeria monocytogenes in produce production and processing environments, said in a statement.If you are immunocompromised, pregnant or over the age of 65, the CDC recommends avoiding foods that are more likely to contain Listeria. These include:Unpasteurized soft cheese—instead opt for harder cheese, cream cheese, or pasteurized soft cheese.Unheated deli meats—instead opt for deli meats that have been reheated.Cut melon that has been left out for more than two hours (or in a refrigerator for over a week) —opt for melon that has been freshly cut.Raw or lightly cooked sprouts—instead opt for sprouts that have been cooked until they are steaming hot.Is there a health issue that's worrying you? Do you have a question about Listeria? Let us know via health@newsweek.com. We can ask experts for advice, and your story could be featured on Newsweek. Uncommon KnowledgeNewsweek is committed to challenging conventional wisdom and finding connections in the search for common ground.Newsweek is committed to challenging conventional wisdom and finding connections in the search for common ground. doFir.push(function(){jQuery(document).ready(function(){if(jQuery('.uncommon-list')[0]){jQuery(jQuery('.uncommon-knowledge')).insertBefore(jQuery('.uncommon-list')[0]);jQuery('.uncommon-knowledge').show();jQuery('.uncommon-list').each(function(){jQuery('#uncommon-knowledge-items-container').append('<div class=\"uncommon-list\">'+jQuery(this).html()+\"</div>\");jQuery(this).remove()}) jQuery('.start-slider').owlCarousel({loop:!1,margin:10,nav:!0,items:1}).on('changed.owl.carousel',function(event){var currentItem=event.item.index;var totalItems=event.item.count;if(currentItem===0){jQuery('.owl-prev').addClass('disabled')}else{jQuery('.owl-prev').removeClass('disabled')} if(currentItem===totalItems-1){jQuery('.owl-next').addClass('disabled')}else{jQuery('.owl-next').removeClass('disabled')}})}})})"
            },
            "requestStatus": true
        },
        {
            "url": "https://www.newsweek.com/mystery-mars-burp-belch-methane-search-life-1863907",
            "scrapedData": {
                "title": "Mystery of Mars' 'Burps' Could Aid Search for Life",
                "article": "Scientists may have solved a long-standing Martian mystery, supporting our never-ending quest to find signs of life on the red planet—Why does Mars burp?In recent years, NASA's Curiosity rover has detected varying levels of methane gas in Mars' atmosphere. While this might sound unremarkable, this fluctuating belch and fart gas has excited scientists because—if its presence on Earth is anything to go by—it could be evidence of microbial life on the red planet.Curiosity is the largest and most capable rover ever sent to Mars, and it has been collecting data since 2012. As part of NASA's Mars Science Laboratory Mission, the rover was sent to answer a simple question: \"Did Mars ever have the right environmental conditions to support small life forms?\"Therefore, the planet's methane emissions offer an exciting opportunity to explore this question. But in order to do so, we need to be able to sample them. And that is a lot easier said than done. A selfie taken by NASA’s Curiosity rover on June 15, 2018. The hunt for signs of life goes on. A selfie taken by NASA’s Curiosity rover on June 15, 2018. The hunt for signs of life goes on. NASA/JPL-Caltech/MSSS function toggleCap(){const shortDesc=document.getElementById('short-cap-description');const fullDesc=document.getElementById('full-cap-description');const readMoreBtn=document.getElementById('read-more-cap');if(fullDesc.style.display==='none'){shortDesc.style.display='none';fullDesc.style.display='inline';readMoreBtn.textContent='Less'}else{shortDesc.style.display='inline';fullDesc.style.display='none';readMoreBtn.textContent='More'}} \"Understanding Mars's methane variations has been highlighted by NASA's Curiosity team as the next key step towards figuring out where it comes from,\" John Ortiz, a graduate student at Los Alamos National Laboratory, said in a statement. \"There are several challenges associated with meeting that goal, and a big one is knowing what time of a given sol (Martian day) is best for Curiosity to perform an atmospheric sampling experiment.\"To better predict these fluctuations, Ortiz and colleagues from across the U.S. used mathematical models to simulate these elusive emissions.The team argues that the gas most likely originates underground, therefore it simulated the path the gas must take through the planet's fractured rock to the surface.They also consider how changes in temperature can affect how much of the methane will stick to pores in the surface rock. And, from these models, they were able to calculate the best time of day to collect methane samples. Read more Scientists discover \"key stepping stone\" to the origin of life This is what alien life in our solar system might look like NASA detects water plume the size of U.S. streaming from Saturn's moon \"Our work suggests several key time windows for Curiosity to collect data,\" Ortiz said. \"We think these offer the best chance of constraining the timing of methane fluctuations, and (hopefully) down the line bringing us closer to understanding where it comes from on Mars.\"In a study published in the journal JGR Planets, the team concluded that the best time to collect this data is prior to sunrise during the upcoming northern summer period, which lies within the candidate time from the Curiosity rover's next sampling campaign.Do you have a tip on a science story that Newsweek should be covering? Do you have a question about Mars? Let us know via science@newsweek.com. Uncommon KnowledgeNewsweek is committed to challenging conventional wisdom and finding connections in the search for common ground.Newsweek is committed to challenging conventional wisdom and finding connections in the search for common ground. doFir.push(function(){jQuery(document).ready(function(){if(jQuery('.uncommon-list')[0]){jQuery(jQuery('.uncommon-knowledge')).insertBefore(jQuery('.uncommon-list')[0]);jQuery('.uncommon-knowledge').show();jQuery('.uncommon-list').each(function(){jQuery('#uncommon-knowledge-items-container').append('<div class=\"uncommon-list\">'+jQuery(this).html()+\"</div>\");jQuery(this).remove()}) jQuery('.start-slider').owlCarousel({loop:!1,margin:10,nav:!0,items:1}).on('changed.owl.carousel',function(event){var currentItem=event.item.index;var totalItems=event.item.count;if(currentItem===0){jQuery('.owl-prev').addClass('disabled')}else{jQuery('.owl-prev').removeClass('disabled')} if(currentItem===totalItems-1){jQuery('.owl-next').addClass('disabled')}else{jQuery('.owl-next').removeClass('disabled')}})}})})"
            },
            "requestStatus": true
        }
    ],
    "created_at": "2024-01-28 10:17:00",
    "updated_at": "2024-01-28 10:19:36"
}
```
### Delete job
**URL** : `/api/jobs/{id}`

**Method** : `DELETE`
```json
{
    "message" : "Job deleted successfully"
}
```
