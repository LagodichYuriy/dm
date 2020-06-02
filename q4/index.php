<?php

# (2) When we delete a customer we also want to delete all of their
# transactions. This needs to be treated as a single operation. What
# methodology would you employ to accomplish this?

# ------------------------------------------------------------------

# There are two classic solutions:
# 1) Foreign keys + cascade DELETE/UPDATE
# 2) Transactions (Savepoints)