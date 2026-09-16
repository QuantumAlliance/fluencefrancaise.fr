<?php

namespace App\Services;

/**
 * SchemaService - Generates Schema.org structured data (JSON-LD) for SEO
 * 
 * Supports common schema types like Article, WebPage, Organization, etc.
 */
class SchemaService
{
    /**
     * Available schema types for pages
     */
    public const SCHEMA_TYPES = [
        'none' => 'None',
        'article' => 'Article',
        'webpage' => 'WebPage',
        'organization' => 'Organization',
        'localbusiness' => 'LocalBusiness',
        'product' => 'Product',
        'service' => 'Service',
        'faq' => 'FAQPage',
        'course' => 'Course',
        'book' => 'Book',
        'event' => 'Event',
        'person' => 'Person',
        'breadcrumblist' => 'BreadcrumbList',
    ];

    protected string $baseUrl;
    protected ?string $siteName;
    protected ?string $siteDescription;
    protected ?string $siteLogo;

    public function __construct()
    {
        $this->baseUrl = config('app.url', url('/'));
        $settings = \App\Models\Settings::pluck('value', 'key')->toArray();
        $this->siteName = $settings['site_name'] ?? config('app.name', 'FocusFrame');
        $this->siteDescription = $settings['site_description'] ?? 'French Learning Platform';
        $this->siteLogo = $settings['site_logo'] ?? null;
    }

    /**
     * Generate JSON-LD script tag for a given schema type and data
     */
    public function generateScript(string $schemaType, array $data = []): string
    {
        $schema = $this->generate($schemaType, $data);
        
        if (empty($schema)) {
            return '';
        }

        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES) . '</script>';
    }

    /**
     * Generate standard Homepage Graph schema (Organization + WebSite + WebPage)
     */
    public function generateHomepageGraph(array $data = []): array
    {
        $url = rtrim($data['url'] ?? $this->baseUrl, '/');
        $siteName = $data['name'] ?? $this->siteName;
        $description = $data['description'] ?? $this->siteDescription;
        $logoUrl = isset($data['logo']) ? $data['logo'] : ($this->siteLogo ? $this->baseUrl . '/storage/' . $this->siteLogo : null);

        $graph = [];

        // 1. Organization
        $orgNode = [
            '@type' => 'Organization',
            '@id' => $url . '/#organization',
            'name' => $siteName,
            'url' => $url,
            'logo' => $logoUrl ? [
                '@type' => 'ImageObject',
                '@id' => $url . '/#logo',
                'url' => $logoUrl,
                'contentUrl' => $logoUrl,
                'caption' => $siteName,
                'inLanguage' => $data['language'] ?? 'en',
            ] : null,
        ];
        
        // Add ImageObject independent node for logo to be referenced clearly
        if ($logoUrl) {
            $orgNode['image'] = ['@id' => $url . '/#logo'];
        }

        if (!empty($data['socialLinks'])) {
            $orgNode['sameAs'] = $data['socialLinks'];
        }
        
        $contactPoint = [];
        if (!empty($data['phone'])) $contactPoint['telephone'] = $data['phone'];
        if (!empty($data['email'])) $contactPoint['email'] = $data['email'];
        
        if (!empty($contactPoint)) {
            $contactPoint['@type'] = 'ContactPoint';
            $contactPoint['contactType'] = 'customer service';
            $orgNode['contactPoint'] = $contactPoint;
        }

        $graph[] = $orgNode;

        // 2. WebSite
        $websiteNode = [
            '@type' => 'WebSite',
            '@id' => $url . '/#website',
            'url' => $url,
            'name' => $siteName,
            'publisher' => ['@id' => $url . '/#organization'],
            'inLanguage' => $data['language'] ?? 'en',
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => $url . '/?s={search_term_string}',
                'query-input' => 'required name=search_term_string',
            ],
        ];
        $graph[] = $websiteNode;

        // 3. WebPage (The Homepage itself)
        $webpageNode = [
            '@type' => 'WebPage',
            '@id' => $url . '/#webpage',
            'url' => $url . '/',
            'name' => $data['title'] ?? $siteName,
            'description' => $description,
            'about' => ['@id' => $url . '/#organization'],
            'isPartOf' => ['@id' => $url . '/#website'],
            'inLanguage' => $data['language'] ?? 'en',
            'datePublished' => $data['datePublished'] ?? null,
            'dateModified' => $data['dateModified'] ?? null,
        ];
        if ($logoUrl) {
            $webpageNode['primaryImageOfPage'] = ['@id' => $url . '/#logo'];
        }
        $graph[] = $webpageNode;

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph
        ];
    }

    /**
     * Generate schema data array for a given type
     */
    public function generate(string $schemaType, array $data = []): array
    {
        $schemaType = strtolower($schemaType);

        if ($schemaType === 'none' || !isset(self::SCHEMA_TYPES[$schemaType])) {
            return [];
        }

        $method = 'generate' . ucfirst($schemaType);
        
        if (method_exists($this, $method)) {
            return $this->$method($data);
        }

        // Default WebPage schema
        return $this->generateWebpage($data);
    }

    /**
     * Generate Organization schema
     */
    public function generateOrganization(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $data['name'] ?? $this->siteName,
            'url' => $data['url'] ?? $this->baseUrl,
            'logo' => $data['logo'] ?? ($this->siteLogo ? $this->baseUrl . '/storage/' . $this->siteLogo : null),
            'description' => $data['description'] ?? $this->siteDescription,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $data['phone'] ?? null,
                'email' => $data['email'] ?? null,
                'contactType' => 'customer service',
            ],
            'sameAs' => $data['socialLinks'] ?? [],
        ];
    }

    /**
     * Generate WebPage schema
     */
    public function generateWebpage(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebPage',
            'name' => $data['name'] ?? $data['title'] ?? $this->siteName,
            'description' => $data['description'] ?? $this->siteDescription,
            'url' => $data['url'] ?? url()->current(),
            'isPartOf' => [
                '@type' => 'WebSite',
                'name' => $this->siteName,
                'url' => $this->baseUrl,
            ],
            'datePublished' => $data['datePublished'] ?? null,
            'dateModified' => $data['dateModified'] ?? null,
        ];
    }

    /**
     * Generate Article/WebPage Graph schema with full context
     */
    public function generateArticleGraph(array $data = []): array
    {
        $url = rtrim($this->baseUrl, '/');
        $pageUrl = $data['url'] ?? url()->current();
        $siteName = $this->siteName;
        $logoUrl = $this->siteLogo ? $this->baseUrl . '/storage/' . $this->siteLogo : null;

        $graph = [];

        // 1. Organization
        $graph[] = [
            '@type' => 'Organization',
            '@id' => $url . '/#organization',
            'name' => $siteName,
            'url' => $url,
            'logo' => $logoUrl ? [
                '@type' => 'ImageObject',
                '@id' => $url . '/#logo',
                'url' => $logoUrl,
                'contentUrl' => $logoUrl,
                'caption' => $siteName,
                'inLanguage' => $data['language'] ?? 'en',
            ] : null,
        ];

        // 2. WebSite
        $graph[] = [
            '@type' => 'WebSite',
            '@id' => $url . '/#website',
            'url' => $url,
            'name' => $siteName,
            'publisher' => ['@id' => $url . '/#organization'],
            'inLanguage' => $data['language'] ?? 'en',
        ];

        // 3. WebPage or Article
        $pageType = $data['type'] ?? 'WebPage';
        $pageNode = [
            '@type' => $pageType,
            '@id' => $pageUrl . '#webpage',
            'url' => $pageUrl,
            'name' => $data['title'] ?? $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'isPartOf' => ['@id' => $url . '/#website'],
            'about' => ['@id' => $url . '/#organization'],
            'inLanguage' => $data['language'] ?? 'en',
            'datePublished' => $data['datePublished'] ?? null,
            'dateModified' => $data['dateModified'] ?? null,
        ];

        if ($logoUrl) {
            $pageNode['primaryImageOfPage'] = ['@id' => $url . '/#logo'];
        }

        // If it's an Article, add publisher and author
        if ($pageType === 'Article') {
            $pageNode['headline'] = $data['title'] ?? $data['name'] ?? '';
            $pageNode['publisher'] = ['@id' => $url . '/#organization'];
            $pageNode['author'] = [
                '@type' => 'Organization',
                '@id' => $url . '/#organization',
            ];
            $pageNode['mainEntityOfPage'] = ['@id' => $pageUrl . '#webpage'];
        }

        $graph[] = $pageNode;

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph
        ];
    }

    /**
     * Generate Collection Page Graph (for Books, Products, etc.)
     */
    public function generateCollectionGraph(array $data = []): array
    {
        $url = rtrim($this->baseUrl, '/');
        $pageUrl = $data['url'] ?? url()->current();
        $siteName = $this->siteName;
        $logoUrl = $this->siteLogo ? $this->baseUrl . '/storage/' . $this->siteLogo : null;

        $graph = [];

        // 1. Organization
        $graph[] = [
            '@type' => 'Organization',
            '@id' => $url . '/#organization',
            'name' => $siteName,
            'url' => $url,
            'logo' => $logoUrl ? [
                '@type' => 'ImageObject',
                '@id' => $url . '/#logo',
                'url' => $logoUrl,
                'contentUrl' => $logoUrl,
                'caption' => $siteName,
                'inLanguage' => $data['language'] ?? 'en',
            ] : null,
        ];

        // 2. WebSite
        $graph[] = [
            '@type' => 'WebSite',
            '@id' => $url . '/#website',
            'url' => $url,
            'name' => $siteName,
            'publisher' => ['@id' => $url . '/#organization'],
            'inLanguage' => $data['language'] ?? 'en',
        ];

        // 3. CollectionPage
        $collectionNode = [
            '@type' => 'CollectionPage',
            '@id' => $pageUrl . '#webpage',
            'url' => $pageUrl,
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'isPartOf' => ['@id' => $url . '/#website'],
            'about' => ['@id' => $url . '/#organization'],
            'inLanguage' => $data['language'] ?? 'en',
        ];

        // Add ItemList if items provided
        if (isset($data['items']) && !empty($data['items'])) {
            $collectionNode['mainEntity'] = [
                '@type' => 'ItemList',
                'numberOfItems' => count($data['items']),
                'itemListElement' => $data['items'],
            ];
        }

        if ($logoUrl) {
            $collectionNode['primaryImageOfPage'] = ['@id' => $url . '/#logo'];
        }

        $graph[] = $collectionNode;

        return [
            '@context' => 'https://schema.org',
            '@graph' => $graph
        ];
    }

    /**
     * Generate Article schema
     */
    public function generateArticle(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $data['title'] ?? $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'author' => [
                '@type' => 'Organization',
                'name' => $data['author'] ?? $this->siteName,
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $this->siteLogo ? $this->baseUrl . '/storage/' . $this->siteLogo : null,
                ],
            ],
            'datePublished' => $data['datePublished'] ?? null,
            'dateModified' => $data['dateModified'] ?? null,
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => $data['url'] ?? url()->current(),
            ],
        ];
    }

    /**
     * Generate Course schema
     */
    public function generateCourse(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Course',
            'name' => $data['title'] ?? $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'provider' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
                'sameAs' => $this->baseUrl,
            ],
            'offers' => isset($data['price']) ? [
                '@type' => 'Offer',
                'price' => $data['price'],
                'priceCurrency' => $data['currency'] ?? 'USD',
            ] : null,
        ];
    }

    /**
     * Generate LocalBusiness schema
     */
    public function generateLocalbusiness(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'LocalBusiness',
            'name' => $data['name'] ?? $this->siteName,
            'description' => $data['description'] ?? $this->siteDescription,
            'url' => $data['url'] ?? $this->baseUrl,
            'telephone' => $data['phone'] ?? null,
            'email' => $data['email'] ?? null,
            'address' => isset($data['address']) ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $data['address']['street'] ?? $data['address'] ?? null,
                'addressLocality' => $data['address']['city'] ?? null,
                'addressCountry' => $data['address']['country'] ?? null,
            ] : null,
            'priceRange' => $data['priceRange'] ?? '$$',
        ];
    }

    /**
     * Generate FAQPage schema
     */
    public function generateFaq(array $data = []): array
    {
        $questions = $data['questions'] ?? [];
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => array_map(function ($faq) {
                return [
                    '@type' => 'Question',
                    'name' => $faq['question'] ?? '',
                    'acceptedAnswer' => [
                        '@type' => 'Answer',
                        'text' => $faq['answer'] ?? '',
                    ],
                ];
            }, $questions),
        ];
    }

    /**
     * Generate Book schema (for books collection)
     */
    public function generateBook(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Book',
            'name' => $data['title'] ?? $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'author' => $data['author'] ?? null,
            'url' => $data['url'] ?? null,
        ];
    }

    /**
     * Generate Product schema
     */
    public function generateProduct(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $data['title'] ?? $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'image' => $data['image'] ?? null,
            'offers' => isset($data['price']) ? [
                '@type' => 'Offer',
                'price' => $data['price'],
                'priceCurrency' => $data['currency'] ?? 'USD',
                'availability' => $data['availability'] ?? 'https://schema.org/InStock',
            ] : null,
        ];
    }

    /**
     * Generate Service schema
     */
    public function generateService(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $data['title'] ?? $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'provider' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
            ],
            'offers' => isset($data['price']) ? [
                '@type' => 'Offer',
                'price' => $data['price'],
                'priceCurrency' => $data['currency'] ?? 'USD',
            ] : null,
        ];
    }

    /**
     * Generate BreadcrumbList schema
     */
    public function generateBreadcrumblist(array $data = []): array
    {
        $items = $data['items'] ?? [];
        
        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_map(function ($item, $index) {
                return [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'name' => $item['name'] ?? '',
                    'item' => $item['url'] ?? '',
                ];
            }, $items, array_keys($items)),
        ];
    }

    /**
     * Generate Event schema
     */
    public function generateEvent(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $data['title'] ?? $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'startDate' => $data['startDate'] ?? null,
            'endDate' => $data['endDate'] ?? null,
            'location' => isset($data['location']) ? [
                '@type' => 'Place',
                'name' => $data['location'],
            ] : null,
            'organizer' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
            ],
        ];
    }

    /**
     * Generate Person schema
     */
    public function generatePerson(array $data = []): array
    {
        return [
            '@context' => 'https://schema.org',
            '@type' => 'Person',
            'name' => $data['name'] ?? '',
            'jobTitle' => $data['jobTitle'] ?? null,
            'email' => $data['email'] ?? null,
            'telephone' => $data['phone'] ?? null,
            'image' => $data['image'] ?? null,
            'worksFor' => [
                '@type' => 'Organization',
                'name' => $this->siteName,
            ],
        ];
    }

    /**
     * Get available schema types for dropdown
     */
    public static function getSchemaTypes(): array
    {
        return self::SCHEMA_TYPES;
    }
}
