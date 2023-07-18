<div class="bottom-controls">
    <div class="js-prev js-stepper-button link-btn" title="Previous Step" data-dir="back" style="opacity: 0.4; pointer-events: none;">
        <i class="material-icons">arrow_back</i>
        Back
    </div>

    @if (!isset($signoffForm))
    <a class="secondary-btn" href="{{ BladeHelper::backOr(route("{$routePrefix}.index")) }}" title="Cancel">
        <i class="material-icons">cancel</i>
        Cancel
    </a>

    <button type="submit" class="js-submit accent-btn" name="action" value="submit" title="Submit" style="display: none;">
        <i class="material-icons">save</i>
        Submit
    </button>
    @endif

    <div class="js-next js-stepper-button primary-btn" title="Next Step" data-dir="next">
        Next
        <i class="material-icons">arrow_forward</i>
    </div>
</div>
