"""Mail disclosure stays in policies while buyer confirmation stays receipt-only."""
import json
from pathlib import Path

root=Path(__file__).resolve().parents[1]
patch=json.loads((root/'data/request-email-policy-patch.json').read_text(encoding='utf-8'))
for identity,entries in patch.items():
    seed=json.loads((root/'data/utility'/f'{identity}.json').read_text(encoding='utf-8'))
    for entry in entries:
        assert entry['new'] in seed['content'],identity
public=(root/'wp-content/plugins/tio2-products/request-form.php').read_text(encoding='utf-8')
thank=(root/'wp-content/plugins/tio2-products/utility.php').read_text(encoding='utf-8')
assert '_tio2_notification_status' not in public
assert '_tio2_notification_status' not in thank
print('PASS: policy disclosure and buyer-facing mail-status boundary')
