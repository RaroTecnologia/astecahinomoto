@if ($items->hasPages())
<nav id="pagination-nav">
    {{ $items->onEachSide(1)->links() }}
</nav>
@endif