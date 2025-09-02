import json

# Simplify itemmap.json
with open('itemmap.json', 'r', encoding='utf-8') as f:
    full_items = json.load(f)

simplified_items = [
    {"itemId": item["itemId"], "name": item["name_i18n"]["en"]}
    for item in full_items
    if "name_i18n" in item and "en" in item["name_i18n"]
]

with open('itemmap.simple.json', 'w', encoding='utf-8') as f:
    json.dump(simplified_items, f, ensure_ascii=False, indent=2)

print(f"Saved {len(simplified_items)} simplified items to itemmap.simple.json")


# Simplify stagemap.json
with open('stagemap.json', 'r', encoding='utf-8') as f:
    full_stages = json.load(f)

simplified_stages = [
    {
        "stageId": stage["stageId"],
        "code": stage["code"],
        # Some stage entries use 'apCost', others 'sanityCost'—fall back gracefully
        "apCost": stage.get("apCost", stage.get("apCost", 0))
    }
    for stage in full_stages
]

with open('stagemap.simple.json', 'w', encoding='utf-8') as f:
    json.dump(simplified_stages, f, ensure_ascii=False, indent=2)

print(f"Saved {len(simplified_stages)} simplified stages to stagemap.simple.json")
