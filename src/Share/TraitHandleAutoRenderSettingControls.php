<?php

namespace WilokeTeamMember\Share;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Widget_Base;

trait TraitHandleAutoRenderSettingControls
{
	public array $convertTypeElementor
		= [
			'text'            => Controls_Manager::TEXT,
			'number'          => Controls_Manager::NUMBER,
			'textarea'        => Controls_Manager::TEXTAREA,
			'wysiwyg'         => Controls_Manager::WYSIWYG,
			'code'            => Controls_Manager::CODE,
			'hidden'          => Controls_Manager::HIDDEN,
			'switcher'        => Controls_Manager::SWITCHER,
			'popover_toggle'  => Controls_Manager::POPOVER_TOGGLE,
			'select'          => Controls_Manager::SELECT,
			'select2'         => Controls_Manager::SELECT2,
			'choose'          => Controls_Manager::CHOOSE,
			'color'           => Controls_Manager::COLOR,
			'icons'           => Controls_Manager::ICONS,
			'image'           => Controls_Manager::MEDIA,
			'font'            => Controls_Manager::FONT,
			'dateTime'        => Controls_Manager::DATE_TIME,
			'gallery'         => Controls_Manager::GALLERY,
			'array'           => Controls_Manager::REPEATER,
			'tabs'            => Controls_Manager::TABS,
			'tab'             => Controls_Manager::TAB,
			'section'         => Controls_Manager::TAB_CONTENT,
			'media'           => Controls_Manager::MEDIA,
			'url'             => Controls_Manager::URL,
			'slide'           => Controls_Manager::SLIDER,
			'wil_custom_post' => 'wil-custom-post'
		];

	public function handleFieldsAddControls($aFields, $hadTypeRepeater = false): array
	{
		$aData = [];
		if (empty($aFields)) {
			return [];
		}
		if ($hadTypeRepeater) {
			$oRepeater = new Repeater();
		}
		foreach ($aFields as $aItem) {
			if ($hadTypeRepeater) {
				$aDataItems = $aItem;
				$aDataItems['default'] = $this->convertSwitchToBoolean($aItem['default'], 'in');
				$oRepeater->add_control(
					$aItem['id'],
					$aItem
				);

			} else {
				$aRawItem = $aItem;
				$aRawItem['type'] = $this->convertTypeElementor[$aItem['type']];
				$aDataItems['default'] = $this->convertSwitchToBoolean($aItem['default'], 'in');
				$aData[] = $aRawItem;
			}
		}

		return $hadTypeRepeater ? $oRepeater->get_controls() : $aData;
	}

	protected function convertSwitchToBoolean($value, $status = 'out')
	{
		if ($status == 'in') {
			if (is_bool($value)) {
				return $value ? 'yes' : 'no';
			}

		} else {
			if (in_array($value, ['yes', 'no'])) {
				return $value == 'yes';
			}
		}
		return $value;
	}

	public function handle(array $aData, Widget_Base $that)
	{
		foreach ($aData as $aSession) {
			if ($aSession['type'] == 'section') {
				$aSessionField = $aSession;
				$aSessionField['tab'] = $this->convertTypeElementor[$aSession['type']];
				$that->start_controls_section(
					$aSession['id'],
					$aSessionField
				);

				if (!empty($aSession['fields'])) {
					foreach ($aSession['fields'] as $aFields) {

						if ($aFields['type'] == 'tabs') {
							$that->start_controls_tabs(
								$aFields['id']
							);

							if (!empty($aFields['fields'])) {

								foreach ($aFields['fields'] as $aItemFields) {

									if ($aItemFields['type'] == 'tab') {
										$that->start_controls_tab(
											$aItemFields['id'],
											$aItemFields
										);
										if (!empty($aItemFields['fields'])) {

											foreach ($aItemFields['fields'] as $aField) {
												$aDataFieldTab = $aField;
												$aDataFieldTab['default']
													= $this->convertSwitchToBoolean($aField['default'], 'in');
												$that->add_control(
													$aField['id'],
													$aField
												);
											}
										}
										$that->end_controls_tab();
									} else {
										$aFieldsControl = $aItemFields;
										$aFieldsControl['type'] = $this->convertTypeElementor[$aItemFields['type']];
										$aFieldsControl['fields']
											= $this->handleFieldsAddControls($aItemFields['fields'] ?? [],
											$aItemFields['type'] == 'array');
										$that->add_control(
											$aItemFields['id'],
											$aFieldsControl
										);
									}

								}
							}
							$that->end_controls_tabs();
						} else {
							$aFieldsControl = $aFields;
							$aFieldsControl['type'] = $this->convertTypeElementor[$aFields['type']];
							if ($aFieldsControl['type'] == 'popover_toggle' && !empty($aFieldsControl['fields'])) {
								$this->add_control(
									$aFieldsControl['id'],
									$aFieldsControl
								);
								$this->start_popover();
								foreach ($aFieldsControl['fields'] as $aFieldsItems) {
									$this->add_control(
										$aFieldsItems['id'],
										$aFieldsItems
									);
								}
								$this->end_popover();
							} else {
								$aFieldsControl['fields'] = $this->handleFieldsAddControls($aFields['fields'] ?? [],
									$aFieldsControl['type'] == 'array');
								$aFieldsControl['default'] = $this->convertSwitchToBoolean($aFields['default']??'', 'in');
								if (isset($aFields['condition'])&&!empty($aFields['condition'])){
									foreach ($aFields['condition'] as $key=>$values){
										$aFieldsControl['condition'][$key]=$this->convertSwitchToBoolean($values, 'in');;
									}
								}
								$that->add_control(
									$aFieldsControl['id'],
									$aFieldsControl
								);
							}
						}
					}
				}
				$that->end_controls_section();
			}
		}
	}

	public function parseItems($aSettings)
	{
		$aItems = [];
		$aDataFields = [];
		$aConfigs = $this->getDataConfigField();

		foreach ($aConfigs as $aSection) {
			if (!empty($aSection['fields'])) {

				foreach ($aSection['fields'] as $aFields) {
					//var_dump(is_array($aSettings[$aFields['id']]));
					if (is_array($aSettings[$aFields['id']])) {
						// data cua cac repeater
						$aResult = [];
						//lay array field name
						if (!empty($aFields['fields'])) {
							$aNameField = [];
							foreach ($aFields['fields'] as $aItem) {
								$aNameField[$aItem['name']] = $aItem['type'];
							}
							foreach ($aSettings[$aFields['id']] as $aItemDataFields) {
								$aRawResult = [];
								foreach ($aNameField as $name => $type) {
									if (is_array($aItemDataFields[$name])) {
										$aRawResult[$name] = $aItemDataFields[$name]['value'] ??
											$aItemDataFields[$name]['url'] ?? "";
									} else {
										$valueFields = ($type == 'switcher')
											? isset($aItemDataFields[$name]) && !empty($aItemDataFields[$name]) :
											$aItemDataFields[$name];
										$aRawResult[$name] = $valueFields;
									}
								}

								$aResult[] = $aRawResult;

							}
							$aDataFields[$aFields['name']] = $aResult;
						} else {
							$aDataFields[$aFields['name']] = $aSettings[$aFields['id']]['value'] ??
								$aSettings[$aFields['id']]['url'] ?? "";
						}
					} else {
						$valueFields = ($aFields['type'] == 'switcher') ? isset($aSettings[$aFields['id']]) && !empty
							($aSettings[$aFields['id']]) && ($aSettings[$aFields['id']] != 'no')
							: $aSettings[$aFields['id']];
						$aDataFields[$aFields['name']] = $valueFields;
					}
				}
			}
			$aItems[$aSection['name']] = $aDataFields;
			$aDataFields = [];
		}

		return $aItems;
	}

}