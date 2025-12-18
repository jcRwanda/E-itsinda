// Helper functions for time / slot calculations

// Returns current POSIX slot as integer
fn now_slot(tx_validity: Interval) -> Int {
  let Interval { lower_bound: IntervalBound { bound_type, .. }, .. } = tx_validity

  when bound_type is {
    Finite(t) -> t
    _ -> fail @"Cannot determine current slot"
  }
}

// Add periods (slots) to a date
fn add_period(date: Int, period: Int) -> Int {
  date + period
}
