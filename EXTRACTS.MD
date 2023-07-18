# Vendor Cost Change

```sql
select
    l.id,
    p.stock_id,
    b.name as brand_name,
    json_extract(l.properties, "$.landed_cost") as landed_cost,
    json_extract(l.properties, "$.old_landed_cost") as old_landed_cost,
    json_extract(l.properties, "$.price_change_date") as price_change_date,
    json_extract(l.properties, "$.price_change_reason") as price_change_reason,
    p.updated_at
from
    ledgers l
    inner join users u on l.user_id = u.id
    inner join products p on l.recordable_id = p.id
    inner join brands b on p.brand_id = b.id
where
    l.recordable_type = 'App\\Models\\Product'
    and json_length(l.modified) > 0
    and json_type(json_extract(l.properties, '$.landed_cost')) <> 'NULL'
    and json_type(json_extract(l.properties, '$.price_change_reason')) <> 'NULL'
    and json_type(json_extract(l.properties, '$.price_change_date')) <> 'NULL'
    and (
        json_contains(l.modified, '["landed_cost"]') = 1
            or json_contains(l.modified, '["price_change_date"]') = 1
            or json_contains(l.modified, '["price_change_reason"]') = 1
    )
    and json_extract(l.properties, '$.landed_cost') <> json_extract(l.properties, '$.old_landed_cost')
    and p.updated_at >= '2022-01-01'
    order by p.stock_id, l.id
```

# Landed cost by year

Only includes ACTIVE products (AS/400 status = 'A').  It filters out products with stock IDs beginning with a period ('.').

Add the following block for each additional year, e.g.:

```sql
(
	SELECT
		case
			when json_extract(l.properties,
			"$.landed_cost") = CAST('null' AS JSON) then null
			else
		 cast(json_extract(l.properties, "$.landed_cost") as decimal(10, 2))
		end AS landed_cost
	FROM
		ledgers l
	WHERE
		l.recordable_type = 'App\\Models\\Product'
		and l.recordable_id = p.id
		and json_extract(l.properties,
		"$.landed_cost") <> cast('null' as JSON)
		and l.created_at < '2022-01-01'
	order by
		l.created_at desc
	limit 1
		) as landed_cost_2021
```

```sql
select
	p.id,
	p.stock_id,
	p.name,
	b.brand_number,
	b.name as brand_name,
	p.landed_cost as current_landed_cost,
	p.created_at as product_created_at,
	(
	SELECT
		case
			when json_extract(l.properties,
			"$.landed_cost") = CAST('null' AS JSON) then null
			else
		 cast(json_extract(l.properties, "$.landed_cost") as decimal(10, 2))
		end AS landed_cost
	FROM
		ledgers l
	WHERE
		l.recordable_type = 'App\\Models\\Product'
		and l.recordable_id = p.id
		and json_extract(l.properties,
		"$.landed_cost") <> cast('null' as JSON)
		and l.created_at < '2022-01-01'
	order by
		l.created_at desc
	limit 1
		) as landed_cost_2021,
		(
	SELECT
		case
			when json_extract(l.properties,
			"$.landed_cost") = CAST('null' AS JSON) then null
			else
		 cast(json_extract(l.properties, "$.landed_cost") as decimal(10, 2))
		end AS landed_cost
	FROM
		ledgers l
	WHERE
		l.recordable_type = 'App\\Models\\Product'
		and l.recordable_id = p.id
		and json_extract(l.properties,
		"$.landed_cost") <> cast('null' as JSON)
			and l.created_at < '2023-01-01'
		order by
				l.created_at desc
		limit 1
		) as landed_cost_2022
from
	products p
inner join brands b on
	p.brand_id = b.id
inner join as400_stock_data asd on
	asd.product_id = p.id
where
	p.state = 1
	and asd.status = 'A'
	and p.stock_id not like '.%'
```
