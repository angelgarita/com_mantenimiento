<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_foos
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Installer\InstallerAdapter;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Log\Log;
use \Joomla\CMS\Installer\InstallerScript;
/**
 * Script file of Foo Component
 *
 * @since  __BUMP_VERSION__
 */

class Com_mantenimientoInstallerScript extends InstallerScript
{
	/**
	 * Minimum Joomla version to check
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */
	private $minimumJoomlaVersion = '4.0';
	/**
	 * Minimum PHP version to check
	 *
	 * @var    string
	 * @since  __BUMP_VERSION__
	 */

	private $minimumPHPVersion = '8.0.14';

	/**
	 * Method to install the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function install($parent): bool
	{
		echo Text::_('Instalado componente com_mantenimiento 1.0.4');
		return true;

	}

	/**
	 * Method to uninstall the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 */
	public function uninstall($parent): bool
	{
		echo Text::_('Desinstalado componente com_mantenimiento 1.0.4');
		return true;
	}

	/**
	 * Method to update the extension
	 *
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 *
	 */
	public function update($parent): bool
	{

        $this->deleteFolders[] = '/components/com_mantenimiento/tmpl/nuevaestacion';
        $this->deleteFolders[] = '/components/com_mantenimiento/tmpl/nuevomantenimiento';
        $this->deleteFolders[] = '/components/com_mantenimiento/tmpl/listamantenimientos';
        $this->deleteFolders[] = '/components/com_mantenimiento/src/View/Nuevaestacion';
        $this->deleteFolders[] = '/components/com_mantenimiento/src/View/Nuevomantenimiento';
        $this->deleteFolders[] = '/components/com_mantenimiento/src/View/Listamantenimientos';

        $this->deleteFolders[] = '/administrator/components/com_mantenimiento/src/View/Foto';
        $this->deleteFolders[] = '/administrator/components/com_mantenimiento/src/View/Fotos';
        $this->deleteFolders[] = '/administrator/components/com_mantenimiento/tmpl/foto';
        $this->deleteFolders[] = '/administrator/components/com_mantenimiento/tmpl/fotos';



        $this->deleteFiles[] = '/components/com_mantenimiento/tmpl/estaciones/edit.php';
        $this->deleteFiles[] = '/components/com_mantenimiento/tmpl/estaciones/edit.xml';
		$this->deleteFiles[] = '/components/com_mantenimiento/tmpl/mantenimientoform/default.xml';
		$this->deleteFiles[] = '/components/com_mantenimiento/tmpl/estacionform/default.xml';

        $this->deleteFiles[] = '/components/com_mantenimiento/src/Model/ListamantenimientosModel.php';
        $this->deleteFiles[] = '/components/com_mantenimiento/src/Model/NuevaestacionModel.php';
        $this->deleteFiles[] = '/components/com_mantenimiento/src/Model/NuevomantenimientoModel.php';
        $this->deleteFiles[] = '/components/com_mantenimiento/src/Controller/NuevaestacionController.php';
        $this->deleteFiles[] = '/components/com_mantenimiento/src/Controller/NuevomantenimientoController.php';


        $this->deleteFiles[] = '/administrator/components/com_mantenimiento/forms/filter_fotos.xml';
        $this->deleteFiles[] = '/administrator/components/com_mantenimiento/forms/foto.xml';
        $this->deleteFiles[] = '/administrator/components/com_mantenimiento/src/Controller/FotoController.php';
        $this->deleteFiles[] = '/administrator/components/com_mantenimiento/src/Controller/FotosController.php';
        $this->deleteFiles[] = '/administrator/components/com_mantenimiento/src/Model/FotoModel.php';
        $this->deleteFiles[] = '/administrator/components/com_mantenimiento/src/Model/FotosModel.php';
        $this->deleteFiles[] = '/administrator/components/com_mantenimiento/src/Table/FotoTable.php';


		$this->removeFiles();
		echo Text::_('Actualizado componente com_mantenimiento a la versión 1.0.4');
		return true;
	}

	/**
	 * Function called before extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 *
	 * @throws Exception
	 */
	public function preflight($type, $parent): bool
	{
		if ($type !== 'uninstall') {
			// Check for the minimum PHP version before continuing
			if (!empty($this->minimumPHPVersion) && version_compare(PHP_VERSION, $this->minimumPHPVersion, '<')) {
				Log::add(
					Text::sprintf('Versión mínima PHP ', $this->minimumPHPVersion),
					Log::WARNING,
					'jerror'
				);
				return false;
			}

			// Check for the minimum Joomla version before continuing
			if (!empty($this->minimumJoomlaVersion) && version_compare(JVERSION, $this->minimumJoomlaVersion, '<')) {
				Log::add(
					Text::sprintf('Versión mínima Joomla', $this->minimumJoomlaVersion),
					Log::WARNING,
					'jerror'
				);

				return false;
			}
		}

		//echo Text::_('COM_FOOS_INSTALLERSCRIPT_PREFLIGHT');

		return true;
	}

	/**
	 * Function called after extension installation/update/removal procedure commences
	 *
	 * @param   string            $type    The type of change (install, update or discover_install, not uninstall)
	 * @param   InstallerAdapter  $parent  The class calling this method
	 *
	 * @return  boolean  True on success
	 *
	 * @since  __BUMP_VERSION__
	 *
	 */
	public function postflight($type, $parent)
	{
		//echo Text::_('COM_FOOS_INSTALLERSCRIPT_POSTFLIGHT');

		return true;
	}
}
