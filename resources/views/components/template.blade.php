<div class="custom-template">
    <div class="title">Settings</div>
    <div class="custom-content">
        <div class="switcher">
            @foreach (['Logo Header' => 'changeLogoHeaderColor', 'Navbar Header' => 'changeTopBarColor', 'Sidebar' => 'changeSideBarColor'] as $header => $class)
                <div class="switch-block">
                    <h4>{{ $header }}</h4>
                    <div class="btnSwitch">
                        @php
                            $colors = ['dark', 'blue', 'purple', 'light-blue', 'green', 'orange', 'red', 'white', 'dark2', 'blue2', 'purple2', 'light-blue2', 'green2', 'orange2', 'red2'];
                            if ($header == 'Sidebar') {
                                $colors = ['dark', 'white'];
                            }
                        @endphp
                        @foreach ($colors as $color)
                            <button type="button" class="{{ $loop->first ? 'selected ' : '' }}{{ $class }}" data-color="{{ $color }}"></button>
                            @if ($loop->iteration == 8)
                                <br />
                            @endif
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    <div class="custom-toggle">
        <i class="icon-settings"></i>
    </div>
</div>

<script>
    const sparklineData = [
        { selector: "#lineChart", data: [102, 109, 120, 99, 110, 105, 115], lineColor: "#177dff", fillColor: "rgba(23, 125, 255, 0.14)" },
        { selector: "#lineChart2", data: [99, 125, 122, 105, 110, 124, 115], lineColor: "#f3545d", fillColor: "rgba(243, 84, 93, .14)" },
        { selector: "#lineChart3", data: [105, 103, 123, 100, 95, 105, 115], lineColor: "#ffa534", fillColor: "rgba(255, 165, 52, .14)" }
    ];

    sparklineData.forEach(chart => {
        $(chart.selector).sparkline(chart.data, {
            type: "line",
            height: "70",
            width: "100%",
            lineWidth: "2",
            lineColor: chart.lineColor,
            fillColor: chart.fillColor
        });
    });
</script>
